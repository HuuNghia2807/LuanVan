<template>
    <div class="header-type">
        <div class="option-search">
            <span class="title">Chọn Size Giày</span>
            <Dropdown
                :filter="true"
                v-model="size"
                :options="sizes"
                optionLabel="name"
                optionValue="code"
                placeholder="Chọn Size Giày"
            >
                <template #option="slotProps">
                    <div class="option-item">{{ slotProps.option.name }}</div>
                </template>
            </Dropdown>
        </div>
        <div class="option-search">
            <span class="title">Khoảng Giá</span>
            <Dropdown
                :filter="true"
                v-model="price"
                :options="prices"
                optionLabel="name"
                optionValue="code"
                placeholder="Tất cả"
            >
                <template #option="slotProps">
                    <div class="option-item">{{ slotProps.option.name }}</div>
                </template>
            </Dropdown>
        </div>
        <div class="option-search">
            <span class="title">Sắp Xếp Theo</span>
            <Dropdown
                :filter="true"
                v-model="sort"
                :options="sorts"
                optionLabel="name"
                optionValue="code"
                placeholder="Chọn giá hoặc tên"
            >
                <template #option="slotProps">
                    <div class="option-item">{{ slotProps.option.name }}</div>
                </template>
            </Dropdown>
        </div>
        <div class="option-search">
            <Button
                class="p-button-lg p-button-warning"
                type="button"
                label="Tìm Giày Ngay"
                icon="pi pi-search"
                iconPos="right"
                :loading="loading"
                @click="load"
            />
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import Dropdown from "primevue/dropdown";
import Button from "primevue/button";

export default defineComponent({
    components: {
        Dropdown,
        Button,
    },
    setup() {
        const sizes = ref([
            { name: "37", code: 37 },
            { name: "38", code: 38 },
            { name: "39", code: 39 },
        ]);
        const prices = ref([
            { name: "Dưới 3 triệu", code: 3 },
            { name: "Từ 3 đến 5 triệu", code: 4 },
        ]);
        const sorts = ref([
            { name: "Giá thấp đến cao", code: "thap" },
            { name: "Giá cao đến thấp", code: "cao" },
            { name: "Tên A - Z", code: "A" },
            { name: "Tên Z - A", code: "Z" },
        ]);
        const sort = ref("");
        const size = ref();
        const price = ref();
        const loading = ref(false);
        const load = () => {
            loading.value = true;
            setTimeout(() => (loading.value = false), 1000);
        };
        return { sizes, size, prices, price, sorts, sort, loading, load };
    },
});
</script>

<style lang="scss" scoped>
.header-type {
    height: 11rem;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #f1f1f1;
    padding: 0 5rem;

    .option-search {
        display: flex;
        flex: 1;
        flex-direction: column;
        justify-content: center;
        margin: 0 2rem;
    }

    .title {
        font-size: 1.6rem;
        color: #212529;
        font-style: italic;
        font-weight: 400;
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    :deep(.p-dropdown-label) {
        font-size: 1.6rem !important;
        line-height: 1.4rem !important;
        padding: 1rem;
    }
}
.option-item {
    font-size: 1.6rem !important;
}
</style>
