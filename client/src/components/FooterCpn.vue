<template>
  <div class="footer flex justify-content-center">
    <div class="footer-left flex-1">
      <!-- <img src="@/assets/img/logo-2.png" alt="logo-2" class="img-logo" /> -->
      <div class="contact">
        <h3 class="contact-title">THÔNG TIN LIÊN HỆ</h3>
        <span class="text">
          ⛪ Địa chỉ: 192/2 Nguyễn Thái Bình, Phường 12, Quận Tân Bình, Thành
          phố Hồ Chí Minh
        </span>
        <span class="text">Email : cskh.kingshoes.vn@gmail.com</span>
        <router-link to="/" class="text">https://kingshoes.vn/</router-link>
        <router-link to="/" class="text">
          https://twitter.com/KingShoes_vn
        </router-link>
        <router-link to="/" class="text">
          https://instagram.com/KingShoes.vn
        </router-link>
        <router-link to="/" class="text">
          https://facebook.com/pg/www.KingShoes.vn
        </router-link>
        <router-link to="/" class="text">
          https://www.youtube.com/www.KingShoes.vn
        </router-link>
        <router-link to="/" class="text">
          http://bit.ly/KINGSHOES_map
        </router-link>
        <router-link to="/" class="text">
          https://www.tiktok.com/@sneaker_radio
        </router-link>
        <span class="text"
          >☎️ Hotline Bán Hàng: 0909.300.746 - 0909.45.0001</span
        >
        <span class="text">☎️ Hotline CSKH: 0902.368.001</span>
      </div>
    </div>
    <div class="footer-responsive">
      <img
        src="@/assets/img/logoSaleNoti.png"
        alt=""
        class="img-certification"
      />
    </div>
    <div class="footer-right flex-1">
      <div class="flex justify-content-center align-items-center">
        <div class="right flex-1">
          <div>
            <h3 class="contact-title">HỖ TRỢ KHÁCH HÀNG</h3>
            <router-link to="/" class="text">Chăm sóc khách hàng</router-link>
            <router-link to="/" class="text">Thanh toán</router-link>
            <router-link to="/" class="text">Hướng dẫn mua hàng</router-link>
          </div>
        </div>
        <div class="right flex-1">
          <div>
            <h3 class="contact-title">CHÍNH SÁCH</h3>
            <router-link to="/" class="text">Chế độ bảo hành</router-link>
            <router-link to="/" class="text">Chính sách đổi hàng</router-link>
            <router-link to="/" class="text">Bảo mật thông tin</router-link>
            <router-link to="/" class="text">Chính sách giao nhận</router-link>
          </div>
        </div>
      </div>
      <div class="right-down">
        <span class="text">
          Số ĐKKD: 41N8041309 cấp ngày 17/8/2018. Nơi cấp Ủy Ban Nhân Dân Quận
          Tân Bình. Hộ Kinh Doanh: KINGSHOES. Hotline: 0909.300.746
        </span>
        <h2 class="certification">CHỨNG NHẬN</h2>
        <img
          src="@/assets/img/logoSaleNoti.png"
          alt=""
          class="img-certification"
        />
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  setup() {
    return {};
  },
});
</script>

<style lang="scss" scoped>
.footer {
  height: 50rem;
  background-color: #212529;
  padding: 0 3rem;
  .footer-left {
    display: flex;
    align-items: center;
  }

  .footer-responsive {
    display: none;
  }

  .img-logo {
    width: 20rem;
    height: 20rem;
  }

  .contact {
    display: flex;
    flex-direction: column;
    justify-content: center;
    color: #999;
    margin-left: 3rem;
  }

  .contact-title {
    color: var(--white-color);
  }

  .text {
    display: block;
    font-size: 1.4rem;
    margin: 0.6rem 0;
  }
  a.text {
    text-decoration: none;
    color: #999;

    &:hover {
      color: var(--primary-color);
    }
  }

  .footer-right {
    border-left: 1px solid var(--white-color);
    margin: 3rem 0 0 2rem;
    padding: 4rem;

    .right {
      display: flex;
      align-items: center;
      flex-direction: column;
      color: #999;
      height: 18rem;

      .text {
        position: relative;
        display: block;
        font-size: 1.4rem;
        margin: 0.8rem 0;
        overflow: hidden;
      }
    }

    .right-down {
      display: flex;
      align-items: center;
      flex-direction: column;
      .text {
        font-size: 1.6rem;
        color: var(--white-color);
      }
    }
  }
  .certification {
    margin: 2rem 0;
    color: var(--white-color);
  }

  .img-certification {
    width: 30rem;
  }
}
@media screen and (min-width: 769px) and (max-width: 1179px) {
  .footer {
    flex-direction: column;
  }
  .footer-right {
    display: none;
  }
  .footer-responsive {
    display: flex !important;
    justify-content: center !important;
  }
  .img-certification {
    width: 20rem !important;
  }
}

@media screen and (max-width: 768px) {
  .footer {
    flex-direction: column;
  }
  .footer-right {
    display: none;
  }
  .footer-responsive {
    display: flex !important;
    justify-content: center !important;
  }
  .img-certification {
    width: 20rem !important;
  }
  .footer .img-logo {
    width: 0;
    height: 0;
  }
}
</style>
