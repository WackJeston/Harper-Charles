<template>
  <section class="product-page-main web-box">

    <div class="wb-row">
      <div class="wb-images">
        <div v-if="this.count > 0" class="wb-image" @click="showImage(this.selectedImage)"
        :style="{ backgroundImage: 'url(' + this.selectedImage + ')' }"></div>
        <div v-else class="wb-image">
          <i class="fa-solid fa-couch"></i>
        </div>

        <div v-show="this.count > 1" class="image-row-button-container">
          <i class="fa-solid fa-caret-left image-move-buttons" @click="imageRowMove('left')" v-show="this.imageRowWidth > this.imageRowButtonContainerWidth"></i>
          <div class="image-row-container">
            <div class="image-row" :style="{ transform: 'translate3d(' + this.imageRowPosition + 'px, 0, 0)' }">
              <div v-for="(image, i) in this.images" @click="this.selectedImage = image.fileName, selectImage(i)"
              :style="{ backgroundImage: 'url(' + image.fileName + ')' }"></div>
            </div>
            <div v-show="this.count > 1" class="selected-images"
            :style="{ transform: 'translate3d(' + this.imageRowPosition + 'px, 0, 0)' }">
              <div v-for="(image, i) in this.images" class="selected-image" :id="'selected' + i"></div>
            </div>
          </div>
          <i class="fa-solid fa-caret-right image-move-buttons" @click="imageRowMove('right')" v-show="this.imageRowWidth > this.imageRowButtonContainerWidth"></i>
        </div>
      </div>

      <div class="wb-content dk">
        <h3 v-show="this.product.subtitle">{{this.product.subtitle}}</h3>
        <p>#: {{this.product.id}}</p>
        <form @submit.prevent="cartAdd" enctype="multipart/form-data">
          <input type="hidden" name="_token" :value="csrf">

          <div v-for="(variant, i) in this.variants" class="variants-container">
            <label :for="i">{{ variant['title'] }}</label>
						<input type="int" :name="i" :v-model="'input' + i" :id="'input' + i" hidden required>
						<!-- <option v-for="(option, i) in variant['options']" :value="i">{{ option[title] }}</option> -->
						<div class="options-grid">
							<div v-for="(option, ioptions) in variant['options']" class="option option" :id="'option' + i2" @click="document.querySelector('#input' + i).value = option[0]">
								<img v-if="option.type == 'image'" :src="option.fileName" alt="option.fileName">
								<div v-else-if="option.type == 'colour'" :style="{ backgroundColor: option.colour }"></div>
							</div>
						</div>
          </div>

          <div class="bottom-row">
            <h3 id="price">£{{ this.product.price }}</h3>
            <button class="submit" type="submit">Add To Cart</button>
          </div>
        </form>
      </div>
    </div>

    <div class="image-viewer-container">
      <div class="image-viewer" v-show="this.imageView">
        <img class="viewer-image">
        <div class="viewer-overlay"></div>
        <i class="fa-solid fa-xmark" @click="closeImage()"></i>
      </div>
    </div>

    <div id="cartAlert"></div>
  </section>
</template>


<script>
  export default {
    props: [
      'product',
      'images',
      'count',
      'variants',
    ],

    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        imageView: false,
        selectedImage: this.images[0].fileName,
        selectedColor: 'blue',
        lastSelected: 0,
        imageRowPosition: 0,
        imageRowWidth: (40 * this.count) - 5,
        imageRowContainerWidth: 0,
        imageRowButtonContainerWidth: 0,
        selectedVariants: 0,
        alertIndex: 0,
      }
    },

    mounted() {
      if (this.images.length > 0) {
        document.querySelector('#selected0').style.backgroundColor = '#022a34';
        this.imageRowResize();
        window.addEventListener('resize', this.imageRowResize);
      }

      // document.querySelector('.alert-box .fa-xmark').addEventListener('click', function() {
      //   EventTarget.parent('ul').remove();
      // })

      this.alertContainer = document.querySelector('#cartAlert');
    },

    methods: {
      async cartAdd(submitEvent) {
        try {
          for (var i = 0; i < this.variants.length; i++) {
            this.input = document.querySelector('#input' + i);
            if (i == 0) {
              this.selectedVariants = '';
              this.selectedVariants = this.input.value;
            } else {
              this.selectedVariants = this.selectedVariants + ',' + this.input.value;
            }
          }

          this.result = await this.$http.post(
            '/product-pageCartAdd/' + this.product.id + '/' + this.variants.length + '/' + this.selectedVariants,
            { name: "cart-add" }
          );
        } catch (err) {
          console.log(err);
        } finally {
          if (this.result.data['success']) {
            this.cartAlert('Item added to cart.');
          } else {
            window.location.href = '/loginCart';
          }
        }
      },

      cartAlert(message) {
        // this.alertContainer.innerHTML += '<ul id="alert' + this.alertIndex + '" class="alert-box success lt"><i class="fa-solid fa-xmark"></i><li>' + message + '</li></ul>';
        this.alertContainer.innerHTML += '<ul id="alert' + this.alertIndex + '" class="alert-box success lt"><li>' + message + '</li></ul>';
        this.cartAlertRemove(this.alertIndex);
        // this.cartAlertRemoveButton(this.alertIndex);
        this.alertIndex += 1;
      },

      cartAlertRemove(i) {
        setTimeout(() => {
          let alert = document.querySelector('#alert' + i);
          alert.style.transform = 'translate3d(calc(100% + 40px), 0, 0)';
          setTimeout(() => {
            alert.remove();
          }, 700);
        }, 4000);
      },

      // cartAlertRemoveButton(i) {
      //   document.querySelector('#alert' + i + ' .fa-xmark').addEventListener('click', function() {
      //     document.querySelector('#alert' + i).remove();
      //   });
      // },

      showImage(fileName) {
        const imageZone = document.querySelector('.viewer-image');
        imageZone.src = fileName;
        this.imageView = true;
      },

      closeImage() {
        const imageZone = document.querySelector('.viewer-image');
        imageZone.src = '';
        this.imageView = false;
      },

      selectImage(i) {
        document.querySelector('#selected'.concat(this.lastSelected))
          .style.backgroundColor = '#e2e6e9';

        document.querySelector('#selected'.concat(i))
          .style.backgroundColor = '#022a34';

        this.lastSelected = i;
      },

      imageRowMove(direction) {
        if(direction == 'left') {
          if (this.imageRowPosition < 0) {
            if (this.imageRowPosition > -39) {
              this.imageRowPosition = 0;
            } else {
              this.imageRowPosition = parseInt(this.imageRowPosition) + 40;
            }
          }
        } else if(direction == 'right') {
          this.imageRowContainerWidth = document.querySelector('.image-row-container').offsetWidth;
          this.imageRowCalc = this.imageRowWidth - this.imageRowContainerWidth;

          if (Math.abs(this.imageRowPosition) < this.imageRowCalc) {
            if (Math.abs(this.imageRowPosition) > (this.imageRowCalc - 39)) {
              this.imageRowPosition = -Math.abs(this.imageRowCalc);
            } else {
              this.imageRowPosition = parseInt(this.imageRowPosition) - 40;
            }
          }
        }

      },

      imageRowResize() {
        this.imageRowContainerWidth = document.querySelector('.image-row-container').offsetWidth;
        this.imageRowButtonContainerWidth = document.querySelector('.image-row-button-container').offsetWidth;
        this.imageRowCalc = this.imageRowWidth - this.imageRowContainerWidth;

        if (this.imageRowWidth < this.imageRowContainerWidth) {
          this.imageRowPosition = 0;
        } else if (Math.abs(this.imageRowPosition) > this.imageRowCalc) {
          this.imageRowPosition = -Math.abs(this.imageRowCalc);
        }
      },
    },
  };
</script>
