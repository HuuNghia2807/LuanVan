<template>
  <div class="container">
    <TheLoader :is-loading="showLoading" />
    <div class="report-header">
      <ReportCustomerHeadCpn :summary-customer="headReport?.customer" />
      <ReportOrderHeadCpn :summary-order="headReport?.order" />
      <ReportHeadCpn :total="headReport?.priceToday" />
    </div>
    <div class="report-chart">
      <div class="select">
        <my-dropdown
          v-model="selectedView"
          :options="optionView"
          optionLabel="name"
          optionValue="code"
          placeholder="Thống kê theo"
        >
          <template #option="slotProps">
            <div class="text-2xl">
              {{ slotProps.option.name }}
            </div>
          </template>
        </my-dropdown>
        <div class="mx-5">
          <Calendar
            v-if="selectedView === 'year'"
            inputId="yearpicker"
            v-model="dataSort"
            view="year"
            dateFormat="yy"
          />
          <Calendar
            v-else
            inputId="monthpicker"
            v-model="dataSort"
            view="month"
            dateFormat="mm/yy"
          />
        </div>
        <my-button
          label="View"
          class="p-button-raised p-button-text"
          icon="pi pi-search"
          @click="handleView"
        />
      </div>
      <div class="chart-wrap">
        <div class="chart-left">
          <Chart type="bar" :data="dataBar" :options="basicOptions" />
        </div>
        <div class="chart-right">
          <Chart type="pie" :data="dataPie" :options="lightOptions" />
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, reactive, ref } from "vue";
import Chart from "primevue/chart";
import Calendar from "primevue/calendar";
import ReportHeadCpn from "@/Dashboard/components/report/ReportHeadCpn.vue";
import ReportCustomerHeadCpn from "../components/report/ReportCustomerHeadCpn.vue";
import ReportOrderHeadCpn from "../components/report/ReportOrderHeadCpn.vue";
import { useStore } from "vuex";
import { IDataBar, IHeadReport, IReport } from "@/interface/order/order.state";
import TheLoader from "@/components/common/TheLoader.vue";
import { translateForYear } from "@/function/translateOrder";
import { PIE_COLOR, PIE_COLOR_HOVER } from "@/constant/color";

export default defineComponent({
  components: {
    ReportHeadCpn,
    ReportCustomerHeadCpn,
    ReportOrderHeadCpn,
    Chart,
    Calendar,
    TheLoader,
  },
  setup() {
    const store = useStore();
    const showLoading = ref(false);
    const dataLabels = ref([] as any[]);
    const dataData = ref([] as any[]);
    const dataLabelsPie = ref([] as any[]);
    const dataDataPie = ref([] as any[]);
    const dataBar = ref({
      labels: [],
      datasets: [
        {
          label: "Thu Nhập: VNĐ",
          backgroundColor: "#42A5F5",
          data: [],
        },
      ],
    } as IDataBar);
    const basicOptions = ref({
      plugins: {
        legend: {
          labels: {
            color: "#495057",
          },
        },
      },
      scales: {
        x: {
          ticks: {
            color: "#495057",
          },
          grid: {
            color: "#ebedef",
          },
        },
        y: {
          ticks: {
            color: "#495057",
          },
          grid: {
            color: "#ebedef",
          },
        },
      },
    });
    const headReport = ref<IHeadReport>();
    const dataSort = ref(new Date());
    const selectedView = ref("month");
    const optionView = reactive([
      { name: "Năm", code: "year" },
      { name: "Tháng", code: "month" },
    ]);
    const dataPie = ref({
      labels: [],
      datasets: [
        {
          data: [],
          backgroundColor: [],
          hoverBackgroundColor: [],
        },
      ],
    });
    const lightOptions = ref({
      plugins: {
        legend: {
          labels: {
            color: "#495057",
          },
        },
      },
    });

    const handleView = () => {
      if (selectedView.value === "month") {
        getByMonth();
        return;
      }
      getByYear();
    };

    const getByMonth = async () => {
      resetData();
      const firstDay = new Date(
        dataSort.value.getFullYear(),
        dataSort.value.getMonth(),
        1
      );
      const lastDay = new Date(
        dataSort.value.getFullYear(),
        dataSort.value.getMonth() + 1,
        0
      );
      const fDay =
        firstDay.getDate() < 10 ? `0${firstDay.getDate()}` : firstDay.getDate();
      const fMonth =
        firstDay.getMonth() + 1 < 10
          ? `0${firstDay.getMonth() + 1}`
          : firstDay.getMonth() + 1;
      const lDay =
        lastDay.getDate() < 10 ? `0${lastDay.getDate()}` : lastDay.getDate();
      const lMonth =
        lastDay.getMonth() + 1 < 10
          ? `0${lastDay.getMonth() + 1}`
          : lastDay.getMonth() + 1;

      const wMonth = {
        start_date: `${firstDay.getFullYear()}-${fMonth}-${fDay}`,
        end_date: `${lastDay.getFullYear()}-${lMonth}-${lDay}`,
      };
      showLoading.value = true;
      const dataMonth = getReport(wMonth);
      showLoading.value = false;
      if ((await dataMonth).bar.length) {
        (await dataMonth).bar.forEach((ele) => {
          dataLabels.value.push(ele.date);
          dataData.value.push(ele.total);
        });
        (await dataMonth).pie.forEach((ele) => {
          dataLabelsPie.value.push(ele.category);
          dataDataPie.value.push(ele.total);
        });
        assignDataBar(dataLabels.value, dataData.value);
        assignDataPie(dataLabelsPie.value, dataDataPie.value);
      } else {
        assignDataBar([], []);
        assignDataPie([], []);
      }
    };

    const getByYear = async () => {
      resetData();
      const year = {
        start_date: `${dataSort.value.getFullYear()}-01-01`,
        end_date: `${dataSort.value.getFullYear()}-12-31`,
      };
      const dataYear = getReport(year);
      const dataChart = translateForYear((await dataYear).bar);

      if (dataChart.length) {
        dataChart.forEach((ele) => {
          dataLabels.value.push(ele.date);
          dataData.value.push(ele.total);
        });
        (await dataYear).pie.forEach((ele) => {
          dataLabelsPie.value.push(ele.category);
          dataDataPie.value.push(ele.total);
        });
        assignDataBar(dataLabels.value, dataData.value);
        assignDataPie(dataLabelsPie.value, dataDataPie.value);
      } else {
        assignDataBar([], []);
        assignDataPie([], []);
      }
    };

    const assignDataBar = (arrLabel: string[], arrData: number[]) => {
      dataBar.value = {
        labels: arrLabel,
        datasets: [
          {
            label: "Thu Nhập: VNĐ",
            backgroundColor: "#42A5F5",
            data: arrData,
          },
        ],
      };
    };

    const assignDataPie = (arrLabel: any, arrData: any) => {
      const color = [] as any;
      const colorHover = [] as any;
      arrLabel.forEach(() => {
        const rand = Math.floor(Math.random() * PIE_COLOR.length);
        color.push(PIE_COLOR[rand]);
        PIE_COLOR.splice(rand, 1);
        const ranColorHover = PIE_COLOR_HOVER[rand];
        colorHover.push(ranColorHover);
        PIE_COLOR_HOVER.splice(rand, 1);
      });

      dataPie.value = {
        labels: arrLabel,
        datasets: [
          {
            data: arrData,
            backgroundColor: color,
            hoverBackgroundColor: colorHover,
          },
        ],
      };
    };

    const resetData = () => {
      dataLabels.value = [];
      dataData.value = [];
      dataLabelsPie.value = [];
      dataDataPie.value = [];
    };

    const getReport = async (data: any) => {
      showLoading.value = true;
      const result = await store.dispatch("order/getReport", data);
      showLoading.value = false;
      return result as IReport;
    };

    onMounted(async () => {
      showLoading.value = true;
      headReport.value = (await store.dispatch(
        "order/getHeadReport"
      )) as IHeadReport;
      getByMonth();
      showLoading.value = false;
    });
    return {
      dataBar,
      basicOptions,
      dataSort,
      selectedView,
      optionView,
      dataPie,
      lightOptions,
      showLoading,
      headReport,
      handleView,
    };
  },
});
</script>

<style lang="scss" scoped>
:deep(.p-component) {
  font-size: 1.4rem !important;
}
.container {
  padding: 4rem 3rem;
  background-color: rgb(235, 235, 235);
  height: 95vh;

  :deep(.p-dropdown) {
    width: 10rem;
  }

  :deep(.p-inputtext) {
    font-size: 1.4rem;
  }

  .report-header {
    display: flex;
    justify-content: space-between;
    max-height: 10rem;
  }

  .report-chart {
    display: flex;
    flex-direction: column;
    margin-top: 2rem;
    background-color: #fff;
    min-height: 50rem;

    .select {
      display: flex;
      margin: 1.5rem 0 1.5rem 2rem;
    }

    :deep(.p-my-dropdown-label) {
      font-size: 1.6rem !important;
      line-height: 1.4rem !important;
      padding: 1rem;
    }

    .option-item {
      font-size: 1.6rem !important;
    }
  }

  .chart-wrap {
    display: flex;
    width: 100%;
    .chart-left {
      width: 70%;
      height: 30rem;
    }

    .chart-right {
      flex: 1;
    }
  }
}
</style>
