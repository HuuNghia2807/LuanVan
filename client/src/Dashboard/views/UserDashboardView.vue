<template>
  <div class="card">
    <DataTable
      :value="users"
      v-model:selection="selectedUsers"
      dataKey="id"
      :paginator="true"
      :rows="8"
      :filters="filters"
      paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
      currentPageReportTemplate="Showing {first} to {last} of {totalRecords} entries"
      responsiveLayout="scroll"
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
              icon="pi pi-trash"
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

      <Column
        selectionMode="multiple"
        style="width: 3rem"
        :exportable="false"
      ></Column>
      <Column
        header="Ảnh & Tên"
        :showFilterMatchModes="false"
        style="min-width: 14rem"
      >
        <template #body="{ data }">
          <my-avatar
            :image="data.img"
            class="mr-2"
            size="xlarge"
            shape="circle"
            style="vertical-align: middle"
          />
          <span class="image-text">{{ data.name }}</span>
        </template>
      </Column>
      <Column header="Ngày Sinh" dataType="date" style="min-width: 8rem">
        <template #body="{ data }">
          {{ data.birth }}
        </template>
        <template #filter="{ filterModel }">
          <my-calendar
            v-model="filterModel.value"
            dateFormat="mm/dd/yy"
            placeholder="mm/dd/yyyy"
          />
        </template>
      </Column>
      <Column field="email" header="Email" style="min-width: 8rem"></Column>
      <Column
        field="phone"
        header="Số Điện Thoại"
        style="min-width: 8rem"
      ></Column>
      <Column
        field="address"
        header="Địa Chỉ"
        style="min-width: 20rem"
      ></Column>

      <Column :exportable="false" style="min-width: 8rem">
        <template #body="slotProps">
          <my-button
            type="button"
            icon="pi pi-eye"
            class="p-button-rounded p-button-info"
          ></my-button>
          <my-button
            icon="pi pi-pencil"
            class="p-button-rounded p-button-success mx-2"
            @click="editProduct(slotProps.data)"
          />
          <my-button
            icon="pi pi-trash"
            class="p-button-rounded p-button-warning"
            @click="confirmDeleteProduct(slotProps.data)"
          />
        </template>
      </Column>
    </DataTable>
  </div>

  <my-dialog
    v-model:visible="deleteProductDialog"
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
        @click="deleteProductDialog = false"
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
    :is-add-employee="isAddEmployee"
    @close-modal-add-employee="closeModalAddEmployee"
  />
  <my-toast position="top-right" group="tr" />
</template>

<script lang="ts">
import { defineComponent, onMounted, reactive, ref } from "vue";
import InputText from "primevue/inputtext";
import { FilterMatchMode } from "primevue/api";
import { useToast } from "primevue/usetoast";
import Column from "primevue/column";
import store from "@/store";
import AddEmployee from "../components/modal/AddEmployee.vue";

export default defineComponent({
  components: {
    InputText,
    Column,
    AddEmployee,
  },
  setup() {
    const users = ref([
      {
        id: 1,
        img: "https://scontent.fhan5-10.fna.fbcdn.net/v/t1.6435-9/177966357_1021543504919292_3434748594631026863_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=174925&_nc_ohc=e3sNXS-D1jwAX8XLQKR&_nc_oc=AQlxsE6-MWgYG1qgMN9FwPKKZjJFH6kum9Qq3nR5GWE12dFS8ugcqmeIFtYEY5KuwHg&tn=R_GashQiMcIgmxTl&_nc_ht=scontent.fhan5-10.fna&oh=00_AT8VY5OXJMp22medAX-Gm6xbMfIDzlLJ4LYRn2q-53uf-A&oe=635F1F90",
        name: "Đặng Ngọc Kim Xuyến",
        birth: "02/08/2003",
        email: "xuyen@gmail.com",
        phone: "0123456789",
        address: "Xã Gì Đó, Quận Bình Thủy, TP Cần Thơ",
        gender: "Nữ",
      },
      {
        id: 2,
        img: "https://scontent.fhan5-10.fna.fbcdn.net/v/t1.6435-9/177966357_1021543504919292_3434748594631026863_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=174925&_nc_ohc=e3sNXS-D1jwAX8XLQKR&_nc_oc=AQlxsE6-MWgYG1qgMN9FwPKKZjJFH6kum9Qq3nR5GWE12dFS8ugcqmeIFtYEY5KuwHg&tn=R_GashQiMcIgmxTl&_nc_ht=scontent.fhan5-10.fna&oh=00_AT8VY5OXJMp22medAX-Gm6xbMfIDzlLJ4LYRn2q-53uf-A&oe=635F1F90",
        name: "Đặng Ngọc Kim Xuyến",
        birth: "02/08/2003",
        email: "xuyen@gmail.com",
        phone: "0123456789",
        address: "Xã Gì Đó, Quận Bình Thủy, TP Cần Thơ",
        gender: "Nữ",
      },
      {
        id: 3,
        img: "https://scontent.fhan5-10.fna.fbcdn.net/v/t1.6435-9/177966357_1021543504919292_3434748594631026863_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=174925&_nc_ohc=e3sNXS-D1jwAX8XLQKR&_nc_oc=AQlxsE6-MWgYG1qgMN9FwPKKZjJFH6kum9Qq3nR5GWE12dFS8ugcqmeIFtYEY5KuwHg&tn=R_GashQiMcIgmxTl&_nc_ht=scontent.fhan5-10.fna&oh=00_AT8VY5OXJMp22medAX-Gm6xbMfIDzlLJ4LYRn2q-53uf-A&oe=635F1F90",
        name: "Đặng Ngọc Kim Xuyến",
        birth: "02/08/2003",
        email: "xuyen@gmail.com",
        phone: "0123456789",
        address: "Xã Gì Đó, Quận Bình Thủy, TP Cần Thơ",
        gender: "Nữ",
      },
      {
        id: 4,
        img: "https://scontent.fhan5-10.fna.fbcdn.net/v/t1.6435-9/177966357_1021543504919292_3434748594631026863_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=174925&_nc_ohc=e3sNXS-D1jwAX8XLQKR&_nc_oc=AQlxsE6-MWgYG1qgMN9FwPKKZjJFH6kum9Qq3nR5GWE12dFS8ugcqmeIFtYEY5KuwHg&tn=R_GashQiMcIgmxTl&_nc_ht=scontent.fhan5-10.fna&oh=00_AT8VY5OXJMp22medAX-Gm6xbMfIDzlLJ4LYRn2q-53uf-A&oe=635F1F90",
        name: "Đặng Ngọc Kim Xuyến",
        birth: "02/08/2003",
        email: "xuyen@gmail.com",
        phone: "0123456789",
        address: "Xã Gì Đó, Quận Bình Thủy, TP Cần Thơ",
        gender: "Nữ",
      },
      {
        id: 5,
        img: "https://scontent.fhan5-10.fna.fbcdn.net/v/t1.6435-9/177966357_1021543504919292_3434748594631026863_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=174925&_nc_ohc=e3sNXS-D1jwAX8XLQKR&_nc_oc=AQlxsE6-MWgYG1qgMN9FwPKKZjJFH6kum9Qq3nR5GWE12dFS8ugcqmeIFtYEY5KuwHg&tn=R_GashQiMcIgmxTl&_nc_ht=scontent.fhan5-10.fna&oh=00_AT8VY5OXJMp22medAX-Gm6xbMfIDzlLJ4LYRn2q-53uf-A&oe=635F1F90",
        name: "Đặng Ngọc Kim Xuyến",
        birth: "02/08/2003",
        email: "xuyen@gmail.com",
        phone: "0123456789",
        address: "Xã Gì Đó, Quận Bình Thủy, TP Cần Thơ",
        gender: "Nữ",
      },
      {
        id: 1,
        img: "https://scontent.fhan5-10.fna.fbcdn.net/v/t1.6435-9/177966357_1021543504919292_3434748594631026863_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=174925&_nc_ohc=e3sNXS-D1jwAX8XLQKR&_nc_oc=AQlxsE6-MWgYG1qgMN9FwPKKZjJFH6kum9Qq3nR5GWE12dFS8ugcqmeIFtYEY5KuwHg&tn=R_GashQiMcIgmxTl&_nc_ht=scontent.fhan5-10.fna&oh=00_AT8VY5OXJMp22medAX-Gm6xbMfIDzlLJ4LYRn2q-53uf-A&oe=635F1F90",
        name: "Đặng Ngọc Kim Xuyến",
        birth: "02/08/2003",
        email: "xuyen@gmail.com",
        phone: "0123456789",
        address: "Xã Gì Đó, Quận Bình Thủy, TP Cần Thơ",
        gender: "Nữ",
      },
      {
        id: 2,
        img: "https://scontent.fhan5-10.fna.fbcdn.net/v/t1.6435-9/177966357_1021543504919292_3434748594631026863_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=174925&_nc_ohc=e3sNXS-D1jwAX8XLQKR&_nc_oc=AQlxsE6-MWgYG1qgMN9FwPKKZjJFH6kum9Qq3nR5GWE12dFS8ugcqmeIFtYEY5KuwHg&tn=R_GashQiMcIgmxTl&_nc_ht=scontent.fhan5-10.fna&oh=00_AT8VY5OXJMp22medAX-Gm6xbMfIDzlLJ4LYRn2q-53uf-A&oe=635F1F90",
        name: "Đặng Ngọc Kim Xuyến",
        birth: "02/08/2003",
        email: "xuyen@gmail.com",
        phone: "0123456789",
        address: "Xã Gì Đó, Quận Bình Thủy, TP Cần Thơ",
        gender: "Nữ",
      },
      {
        id: 3,
        img: "https://scontent.fhan5-10.fna.fbcdn.net/v/t1.6435-9/177966357_1021543504919292_3434748594631026863_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=174925&_nc_ohc=e3sNXS-D1jwAX8XLQKR&_nc_oc=AQlxsE6-MWgYG1qgMN9FwPKKZjJFH6kum9Qq3nR5GWE12dFS8ugcqmeIFtYEY5KuwHg&tn=R_GashQiMcIgmxTl&_nc_ht=scontent.fhan5-10.fna&oh=00_AT8VY5OXJMp22medAX-Gm6xbMfIDzlLJ4LYRn2q-53uf-A&oe=635F1F90",
        name: "Đặng Ngọc Kim Xuyến",
        birth: "02/08/2003",
        email: "xuyen@gmail.com",
        phone: "0123456789",
        address: "Xã Gì Đó, Quận Bình Thủy, TP Cần Thơ",
        gender: "Nữ",
      },
      {
        id: 4,
        img: "https://scontent.fhan5-10.fna.fbcdn.net/v/t1.6435-9/177966357_1021543504919292_3434748594631026863_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=174925&_nc_ohc=e3sNXS-D1jwAX8XLQKR&_nc_oc=AQlxsE6-MWgYG1qgMN9FwPKKZjJFH6kum9Qq3nR5GWE12dFS8ugcqmeIFtYEY5KuwHg&tn=R_GashQiMcIgmxTl&_nc_ht=scontent.fhan5-10.fna&oh=00_AT8VY5OXJMp22medAX-Gm6xbMfIDzlLJ4LYRn2q-53uf-A&oe=635F1F90",
        name: "Đặng Ngọc Kim Xuyến",
        birth: "02/08/2003",
        email: "xuyen@gmail.com",
        phone: "0123456789",
        address: "Xã Gì Đó, Quận Bình Thủy, TP Cần Thơ",
        gender: "Nữ",
      },
      {
        id: 5,
        img: "https://scontent.fhan5-10.fna.fbcdn.net/v/t1.6435-9/177966357_1021543504919292_3434748594631026863_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=174925&_nc_ohc=e3sNXS-D1jwAX8XLQKR&_nc_oc=AQlxsE6-MWgYG1qgMN9FwPKKZjJFH6kum9Qq3nR5GWE12dFS8ugcqmeIFtYEY5KuwHg&tn=R_GashQiMcIgmxTl&_nc_ht=scontent.fhan5-10.fna&oh=00_AT8VY5OXJMp22medAX-Gm6xbMfIDzlLJ4LYRn2q-53uf-A&oe=635F1F90",
        name: "Đặng Ngọc Kim Xuyến",
        birth: "02/08/2003",
        email: "xuyen@gmail.com",
        phone: "0123456789",
        address: "Xã Gì Đó, Quận Bình Thủy, TP Cần Thơ",
        gender: "Nữ",
      },
    ]);
    const selectUser = ref("customers");
    const userType = reactive([
      {
        name: "Khách Hàng",
        code: "customers",
      },
      {
        name: "Nhân viên",
        code: "employee",
      },
    ]);
    const selectedUsers = ref([] as any[]);
    const isAddEmployee = ref(false);
    const filters = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    });
    const deleteProductDialog = ref(false);
    const productDialog = ref(false);
    const product = ref<any>({});
    const toast = useToast();
    const deleteProductsDialog = ref(false);

    const confirmDeleteSelected = () => {
      deleteProductDialog.value = true;
    };
    const editProduct = (prod: any) => {
      product.value = { ...prod };
      productDialog.value = true;
    };
    const deleteProduct = () => {
      if (selectedUsers.value.length) {
        deleteSelectedProducts();
      }

      users.value = users.value.filter((val) => val.id !== product.value.id);
      deleteProductDialog.value = false;
      product.value = {};
      toast.add({
        severity: "success",
        summary: "Successful",
        detail: "Product Deleted",
        group: "tr",
        life: 3000,
      });
    };
    const deleteSelectedProducts = () => {
      users.value = users.value.filter(
        (val) => !selectedUsers.value.includes(val)
      );
      deleteProductsDialog.value = false;
      selectedUsers.value = [];
      toast.add({
        severity: "success",
        summary: "Successful",
        detail: "Products Deleted",
        group: "tr",
        life: 3000,
      });
    };

    const confirmDeleteProduct = (prod: any) => {
      product.value = prod;
      deleteProductDialog.value = true;
    };

    const handleAddEmployee = () => {
      isAddEmployee.value = true;
    };

    const closeModalAddEmployee = () => {
      isAddEmployee.value = false;
    };

    onMounted(async () => {
      // await store
    });

    return {
      users,
      filters,
      deleteProductDialog,
      productDialog,
      product,
      deleteProductsDialog,
      selectedUsers,
      isAddEmployee,
      closeModalAddEmployee,
      handleAddEmployee,
      confirmDeleteSelected,
      editProduct,
      deleteProduct,
      deleteSelectedProducts,
      confirmDeleteProduct,
      selectUser,
      userType,
    };
  },
});
</script>

<style lang="scss" scoped>
.card {
  background: var(--surface-card);
  padding: 2rem;
  border-radius: 10px;
  margin-bottom: 2rem;
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
