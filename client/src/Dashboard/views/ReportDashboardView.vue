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
import { computed, defineComponent, onMounted, reactive, ref } from "vue";
import Chart from "primevue/chart";
import Calendar from "primevue/calendar";
import ReportHeadCpn from "@/Dashboard/components/report/ReportHeadCpn.vue";
import ReportCustomerHeadCpn from "../components/report/ReportCustomerHeadCpn.vue";
import ReportOrderHeadCpn from "../components/report/ReportOrderHeadCpn.vue";
import { useStore } from "vuex";
import {
  IDataBar,
  IHeadReport,
  IReportByMonth,
} from "@/interface/order/order.state";
import TheLoader from "@/components/common/TheLoader.vue";

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
      labels: ["Nike", "Jordan", "Puma"],
      datasets: [
        {
          data: [300, 50, 100],
          backgroundColor: ["#42A5F5", "#66BB6A", "#FFA726"],
          hoverBackgroundColor: ["#64B5F6", "#81C784", "#FFB74D"],
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
    };

    const getByMonth = async () => {
      dataLabels.value = [];
      dataData.value = [];
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
      const dataMonth = (await store.dispatch(
        "order/getReportByMonth",
        wMonth
      )) as IReportByMonth[];
      if (dataMonth.length) {
        dataMonth.forEach((ele) => {
          dataLabels.value.push(ele.date);
          dataData.value.push(ele.total);
        });
        assignDataBar(dataLabels.value, dataData.value);
      } else {
        assignDataBar([], []);
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
