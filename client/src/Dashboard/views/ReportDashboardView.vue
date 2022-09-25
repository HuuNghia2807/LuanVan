<template>
  <div class="container">
    <div class="report-header">
      <ReportHeadCpn :info="users" />
      <ReportHeadCpn />
      <ReportHeadCpn />
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
            <div class="option-item">{{ slotProps.option.name }}</div>
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
            v-else-if="selectedView === 'month'"
            inputId="monthpicker"
            v-model="dataSort"
            view="month"
            dateFormat="mm/yy"
          />
          <Calendar
            v-else
            inputId="dateformat"
            v-model="dataSort"
            dateFormat="mm-dd-yy"
          />
        </div>
        <my-button
          label="View"
          class="p-button-raised p-button-text"
          icon="pi pi-search"
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
import { defineComponent, reactive, ref } from "vue";
import Chart from "primevue/chart";
import Calendar from "primevue/calendar";
import ReportHeadCpn from "@/Dashboard/components/report/ReportHeadCpn.vue";

export default defineComponent({
  components: {
    ReportHeadCpn,
    Chart,
    Calendar,
  },
  setup() {
    const users = reactive({
      img: "@/Dashboard/assets/icon-images/users-report.png",
    });
    const dataBar = ref({
      labels: [
        "1",
        "2",
        "3",
        "4",
        "5",
        "6",
        "7",
        "8",
        "9",
        "10",
        "11",
        "12",
        "13",
        "14",
        "15",
        "16",
        "17",
        "18",
        "19",
        "20",
        "21",
        "22",
        "23",
        "24",
        "25",
        "26",
        "27",
        "28",
        "29",
        "30",
        "31",
      ],
      datasets: [
        {
          label: "Thu Nhập: VNĐ",
          backgroundColor: "#42A5F5",
          data: [
            65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40, 65, 59, 80,
            81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40, 56, 55, 40,
          ],
        },
      ],
    });
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

    const dataSort = ref();
    const selectedView = ref("year");
    const optionView = reactive([
      { name: "Năm", code: "year" },
      { name: "Tháng", code: "month" },
      { name: "Ngày", code: "day" },
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
    return {
      users,
      dataBar,
      basicOptions,
      dataSort,
      selectedView,
      optionView,
      dataPie,
      lightOptions,
    };
  },
});
</script>

<style lang="scss" scoped>
.container {
  padding: 5rem 3rem;
  background-color: rgb(235, 235, 235);
  height: 95vh;

  .report-header {
    display: flex;
    justify-content: space-between;
    max-height: 10rem;
  }

  .report-chart {
    display: flex;
    flex-direction: column;
    margin-top: 3rem;
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
