<template>
  <TheLoader :is-loading="showLoading" />
  <my-toast />
  <div class="card">
    <DataTable
      :value="userList"
      v-model:selection="selectedUsers"
      dataKey="id"
      :paginator="true"
      :rows="8"
      :filters="filters"
      scrollDirection="both"
      paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
      currentPageReportTemplate="Showing {first} to {last} of {totalRecords} entries"
    >
      <template #header>
        <div
          class="table-header flex flex-column md:flex-row md:justiify-content-between"
        >
          <div class="flex align-items-center">
            <h5 class="mb-2 md:m-0 p-as-md-center">Quản Lý</h5>
            <my-dropdown
              v-model="selectUser"
              :options="userType"
              optionLabel="name"
              optionValue="code"
              style="font-size: 1.6rem !important"
              class="ml-3"
            />
          </div>
          <span class="p-input-icon-left">
            <i class="pi pi-search" />
            <InputText
              v-model="filters['global'].value"
              placeholder="Search..."
            />
          </span>
          <div class="wrap">
            <my-button
              label="Thêm Nhân Viên"
              icon="pi pi-plus"
              class="p-button-success mr-3"
              @click="handleAddEmployee"
            />
            <my-button
              label="Xóa Người Dùng"
              icon="pi pi-trash"
              class="p-button-danger"
              @click="confirmDeleteSelected"
              :disabled="!selectedUsers || !selectedUsers.length"
            />
          </div>
        </div>
      </template>
      <my-column
        selectionMode="multiple"
        style="width: 3rem"
        :exportable="false"
      ></my-column>
      <my-column field="id" header="Mã" style="width: 8rem"></my-column>
      <my-column
        v-if="selectUser === 'customer'"
        header="Trạng Thái"
        field="status"
        :style="{ width: '15rem' }"
        sortable
      >
        <template #body="slotProps">
          <span :class="'customer-badge status-' + slotProps.data.status">{{
            slotProps.data.status.toUpperCase()
          }}</span>
        </template>
      </my-column>
      <my-column header="Ảnh" style="width: 8rem">
        <template #body="slotProps">
          <img
            :src="
              slotProps.data.avatar ||
              require('@/assets/img/avatar_default/default-avatar.png')
            "
            class="img"
          />
        </template>
      </my-column>

      <my-column
        v-for="col of columns"
        :field="col.field"
        :header="col.header"
        :key="col.field"
        :style="{ width: col.width }"
      >
      </my-column>

      <my-column :exportable="false" style="min-width: 15rem">
        <template #body="slotProps">
          <my-button
            icon="pi pi-info"
            class="p-button-rounded mr-4"
            @click="handleViewInfo(slotProps.data)"
          />
          <my-button
            icon="pi pi-trash"
            class="p-button-rounded p-button-warning"
            @click="confirmDeleteUser(slotProps.data)"
          />
        </template>
      </my-column>
    </DataTable>
  </div>

  <my-dialog
    v-model:visible="deleteUserDialog"
    :style="{ width: '450px' }"
    header="Confirm"
    :modal="true"
  >
    <div class="confirmation-content">
      <i class="pi pi-exclamation-triangle mr-3" style="font-size: 3rem" />
      <span v-if="product" style="font-size: 1.6rem"
        >Are you sure you want to delete <b>{{ product.name }}</b
        >?</span
      >
    </div>
    <template #footer>
      <my-button
        label="No"
        icon="pi pi-times"
        class="p-button-text"
        style="font-size: 1.6rem"
        @click="deleteUserDialog = false"
      />
      <my-button
        label="Yes"
        icon="pi pi-check"
        class="p-button-text"
        style="font-size: 1.6rem"
        @click="deleteProduct"
      />
    </template>
  </my-dialog>
  <AddEmployee
    v-if="isAddEmployee"
    :is-add-employee="isAddEmployee"
    @close-modal-add-employee="closeModalAddEmployee"
  />
  <UserInformation
    v-if="isShowInfoCustomer"
    :customer="customer"
    :is-show-customer="isShowInfoCustomer"
    @close-modal="closeModal"
    @change-status-customer="changeStatuscus"
  />

  <EmployeeInformation
    v-if="isShowEmployee"
    :is-show-employee="isShowEmployee"
    :employee="employee"
    @close-modal="closeModal"
    @delegate-employee="delegateEmp"
  />
  <my-toast position="top-right" group="tr" />
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, reactive, ref } from "vue";
import InputText from "primevue/inputtext";
import { FilterMatchMode } from "primevue/api";
import { useToast } from "primevue/usetoast";
import store from "@/store";
import AddEmployee from "../components/modal/AddEmployee.vue";
import TheLoader from "@/components/common/TheLoader.vue";
import { IAllUser } from "@/interface/auth/authentication.state";
import UserInformation from "@/Dashboard/components/modal/UserInformation.vue";
import EmployeeInformation from "@/Dashboard/components/modal/EmployeeInformation.vue";

export default defineComponent({
  components: {
    InputText,
    AddEmployee,
    TheLoader,
    UserInformation,
    EmployeeInformation,
  },
  setup() {
    const users = ref<IAllUser>();
    const selectUser = ref("customer");
    const showLoading = ref(false);
    const userType = reactive([
      {
        name: "Khách Hàng",
        code: "customer",
      },
      {
        name: "Nhân viên",
        code: "employee",
      },
    ]);
    const isShowInfoCustomer = ref(false);
    const customer = ref();
    const isShowEmployee = ref(false);
    const employee = ref();
    const columns = computed(() => {
      if (selectUser.value === "customer") {
        return [
          { field: "fullName", header: "Tên", width: "20rem" },
          { field: "email", header: "Email", width: "15rem" },
          { field: "phone", header: "Số Điện Thoại", width: "15rem" },
          { field: "birth", header: "Ngày Sinh", width: "15rem" },
          { field: "gender", header: "Giới tính", width: "15rem" },
        ];
      }
      return [
        { field: "fullName", header: "Tên", width: "20rem" },
        { field: "email", header: "Email", width: "15rem" },
        { field: "role", header: "Chức vụ", width: "15rem" },
        { field: "phone", header: "Số Điện Thoại", width: "15rem" },
        { field: "birth", header: "Ngày Sinh", width: "15rem" },
        { field: "gender", header: "Giới tính", width: "15rem" },
      ];
    });
    const selectedUsers = ref([] as any[]);
    const isAddEmployee = ref(false);
    const filters = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    });
    const deleteUserDialog = ref(false);
    const productDialog = ref(false);
    const product = ref<any>({});
    const toast = useToast();
    const deleteProductsDialog = ref(false);

    const userList = computed(() => {
      return (
        users.value &&
        (selectUser.value === "customer"
          ? users.value?.customer
          : users.value?.employee)
      );
    });

    const confirmDeleteSelected = () => {
      deleteUserDialog.value = true;
    };

    const deleteProduct = () => {
      if (selectedUsers.value.length) {
        console.log(selectedUsers.value);
      }
    };

    const confirmDeleteUser = (data: any) => {
      console.log(data);
    };

    const handleAddEmployee = () => {
      isAddEmployee.value = true;
    };

    const closeModalAddEmployee = () => {
      isAddEmployee.value = false;
    };

    const handleViewInfo = (user: any) => {
      if (selectUser.value === "customer") {
        customer.value = user;
        isShowInfoCustomer.value = true;
        return;
      }
      employee.value = user;
      isShowEmployee.value = true;
    };

    const closeModal = () => {
      customer.value = undefined;
      isShowInfoCustomer.value = false;
      employee.value = undefined;
      isShowEmployee.value = false;
    };

    const changeStatuscus = async (status_id: number, cus_id: number) => {
      showLoading.value = true;
      await store.dispatch("auth/setStatusCustomer", {
        customer_id: cus_id,
        user_status_id: status_id,
      });
      showLoading.value = false;
      closeModal();
      await loadUser();
      toast.add({
        severity: "success",
        summary: "Thành công",
        detail: "Đổi trạng thái thành công",
        life: 3000,
      });
    };

    const delegateEmp = async (role_id: number, emp_id: number) => {
      showLoading.value = true;
      await store.dispatch("auth/delegateEmp", {
        employee_id: emp_id,
        role_id,
      });
      showLoading.value = false;
      closeModal();
      await loadUser();
      toast.add({
        severity: "success",
        summary: "Thành công",
        detail: "Phân quyền thành công",
        life: 3000,
      });
    };

    const loadUser = async () => {
      showLoading.value = true;
      users.value = await store.dispatch("auth/getAllUser");
      showLoading.value = false;
    };

    onMounted(async () => {
      await loadUser();
    });

    return {
      users,
      userList,
      filters,
      deleteUserDialog,
      productDialog,
      product,
      deleteProductsDialog,
      selectedUsers,
      isAddEmployee,
      showLoading,
      columns,
      customer,
      isShowInfoCustomer,
      isShowEmployee,
      employee,
      delegateEmp,
      changeStatuscus,
      closeModal,
      handleViewInfo,
      closeModalAddEmployee,
      handleAddEmployee,
      confirmDeleteSelected,
      deleteProduct,
      confirmDeleteUser,
      selectUser,
      userType,
    };
  },
});
</script>

<style lang="scss" scoped>
.customer-badge {
  border-radius: 2px;
  padding: 0.25em 0.5rem;
  text-transform: uppercase;
  font-weight: 700;
  font-size: 12px;
  letter-spacing: 0.3px;
}

.customer-badge.status-retired {
  background-color: #a1a2ed;
  color: #373ec6;
}

.customer-badge.status-active {
  background-color: #aaf5b8;
  color: #1ccb16;
}

.customer-badge.status-new {
  background-color: #edee9a;
  color: #cbbf16;
}

.customer-badge.status-blocked {
  background-color: #fddde0;
  color: #c63737;
}
.card {
  background: var(--surface-card);
  padding: 2rem;
  border-radius: 10px;
  margin-bottom: 2rem;
}

.img {
  width: 4rem;
  height: 4rem;
  border-radius: 50%;
  object-fit: contain;
  border: 1px solid #ccc;
}

:deep(.p-component),
:deep(.p-inputtext),
h5 {
  font-size: 1.4rem !important;
}

@keyframes pulse {
  0% {
    background-color: rgba(165, 165, 165, 0.1);
  }
  50% {
    background-color: rgba(165, 165, 165, 0.3);
  }
  100% {
    background-color: rgba(165, 165, 165, 0.1);
  }
}

.product-badge.status-nike {
  background: #c8e6c9;
  color: #256029;
}

.product-badge.status-jordan {
  background: #ffcdd2;
  color: #c63737;
}

.product-badge.status-puma {
  background: #feedaf;
  color: #8a5340;
}

.table-header {
  display: flex;
  align-items: center;
  justify-content: space-between;

  @media screen and (max-width: 960px) {
    align-items: start;
  }
}

.confirmation-content {
  display: flex;
  align-items: center;
  justify-content: center;
}
@media screen and (max-width: 960px) {
  ::v-deep(.p-toolbar) {
    flex-wrap: wrap;

    .p-button {
      margin-bottom: 0.25rem;
    }
  }
}
</style>
