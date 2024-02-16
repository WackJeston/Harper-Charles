<template>
	<div class="wb-row">
		<div class="wb-images">
			<div class="wb-image">
				<div class="wb-image-container">
					<div v-if="this.product.orbitalVisionId != null" v-show="this.selectedImage == '3d-model'" id="viewerContainer"></div>

					<img v-if="this.count > 0 && this.selectedImage != '3d-model'" :src="this.selectedImage" :alt="this.selectedImage" @click="showImage(this.selectedImage)">
					<i v-else-if="this.selectedImage != '3d-model'" class="fa-solid fa-couch"></i>
				</div>
			</div>

			<div v-if="this.imageCount > 1" class="image-row-button-container">
				<i class="fa-solid fa-caret-left image-move-buttons" @click="imageRowMove('left')" v-show="this.imageRowWidth > this.imageRowButtonContainerWidth"></i>
				<div class="image-row-container">
					<div class="image-row" :style="{ transform: 'translate3d(' + this.imageRowPosition + 'px, 0, 0)' }">
						<div v-if="this.product.orbitalVisionId != null" @click="this.selectedImage = '3d-model', selectImage('3d-model')" id="viewerContainerButton"><i class="fa-solid fa-globe"></i></div>
						
						<div v-for="(image, i) in this.images" @click="this.selectedImage = image.fileName, selectImage(i)" :style="{ backgroundImage: 'url(' + image.fileName + ')' }"></div>
					</div>
					<div v-show="this.imageCount > 1" class="selected-images" :style="{ transform: 'translate3d(' + this.imageRowPosition + 'px, 0, 0)' }">
						<div v-if="this.product.orbitalVisionId != null" class="selected-image" :id="'selected3d-model'"></div>
						<div v-for="(image, i) in this.images" class="selected-image" :id="'selected' + i"></div>
					</div>
				</div>
				<i class="fa-solid fa-caret-right image-move-buttons" @click="imageRowMove('right')" v-show="this.imageRowWidth > this.imageRowButtonContainerWidth"></i>
			</div>
		</div>

		<div class="wb-content-container">
			<div class="wb-content dk">
				<h3 v-show="this.product.subtitle">{{this.product.subtitle}}</h3>
				<p>#: {{this.product.id}}</p>
			</div>
			
			<div class="wb-content bg-gray dk" :class="{ 'full-height' : (this.variantCount > 0) }">
				<form @submit.prevent="cartAdd" action="/product-pageBasketAdd" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="_token" :value="csrf">
					<input type="hidden" name="productId" :value="this.product.id">

					<div v-if="this.product.orbitalVisionId != null" style="width: 100%; margin-bottom: 20px;">
						<div id="optionsContainer"></div>

						<input type="hidden" name="configuration" :value="this.configuration">
					</div>

					<div v-for="(variant, i) in this.variants" class="variants-container" :id="'variant-container-' + i">
						<label :for="i">{{ variant['title'] }}</label>

						<select v-if="variant['type'] == 'text'" :name="'variant-' + i" required>
							<option v-for="(option, i2) in variant['options']" :value="i2" :data-variant-id="option.id">{{ option['title'] }}</option>
						</select>

						<div v-else class="options-grid">
							<input type="text" :data-value="variant['selected']" :value="variant['selected']" :name="'variant-' + i" :v-model="'variant-input-' + i" :id="'variant-input-' + i" hidden required>

							<div v-for="(option, i2) in variant['options']" class="option" :class="{ 'selected' : variant['selected'] == option.id }" :data-variant-id="option.id" :id="'option-' + i2"  @click="this.setOption(i, i2)">
								<div class="option-container">
									<img v-if="variant['type'] == 'image'" :src="option.fileName" alt="option.fileName">
									<div v-else-if="variant['type'] == 'colour'" :style="{ backgroundColor: option.colour }"></div>
								</div>

								<small>{{ option.title }}</small>
							</div>
						</div>
					</div>
					
					<small v-if="this.variantCount > 0" class="variant-info">
						Please select from the options above before adding to your cart.
					</small>

					<div class="bottom-row">
						<span id="priceContainer">£{{ this.product.price }}</span>
						<div class="bottom-row-container">
							<input type="number" name="quantity" id="quantity" value="1" min="1" :max="this.product.maxQuantity">
							<button class="submit" type="submit">Add To Basket</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="cartAlert"></div>
</template>


<script>
  export default {
    props: [
      'product',
      'images',
      'count',
      'variants',
			'specs',
			'orbitalvisionkey'
    ],

    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
				imageCount: this.product.orbitalVisionId != null ? parseInt(this.count + 1) :  parseInt(this.count),
        imageView: false,
        selectedImage: (this.count == 0 && this.product.orbitalVisionId == null) ? null : (this.count == 0 ? '3d-model' : this.images[0].fileName),
        selectedColor: 'blue',
        lastSelected: 0,
        imageRowPosition: 0,
        imageRowWidth: (50 * (this.product.orbitalVisionId != null ? parseInt(this.count + 1) :  parseInt(this.count))) - 5,
        imageRowContainerWidth: 0,
        imageRowButtonContainerWidth: 0,
        selectedVariants: 0,
				variantCount: Object.keys(this.variants).length,
				expiviInstance: null,
				configuration: null,
      }
    },

    mounted() {
      if (this.imageCount > 1) {
				document.querySelector('#selected0').classList.add('selected');
        this.imageRowResize();
        window.addEventListener('resize', this.imageRowResize);
      }
    },

    methods: {
			async cartAdd(submitEvent) {
				if (this.product.orbitalVisionId != null) {
					let configurationData = await window.expivi.saveConfiguration(600, 600);
					
					this.configuration = JSON.stringify(configurationData);
				}

				setTimeout(() => {
					submitEvent.target.submit();
				}, 10);
      },

			showImage(url) {
				const imageZone = document.querySelector('.image-viewer');
				const image = document.querySelector('.viewer-image');

				image.src = url;
				
				imageZone.style.display = 'flex';
			},

			closeImage() {
				const imageZone = document.querySelector('.image-viewer');
				const image = document.querySelector('.viewer-image');

				image.src = '';
				
				imageZone.style.display = 'none';
			},

      selectImage(i) {
        document.querySelector('#selected'.concat(this.lastSelected)).classList.remove('selected');

        document.querySelector('#selected'.concat(i)).classList.add('selected');

        this.lastSelected = i;
      },

      imageRowMove(direction) {
        if(direction == 'left') {
          if (this.imageRowPosition < 0) {
            if (this.imageRowPosition > -39) {
              this.imageRowPosition = 0;
            } else {
              this.imageRowPosition = parseInt(this.imageRowPosition) + 50;
            }
          }
        } else if(direction == 'right') {
          this.imageRowContainerWidth = document.querySelector('.image-row-container').offsetWidth;
          this.imageRowCalc = this.imageRowWidth - this.imageRowContainerWidth;

          if (Math.abs(this.imageRowPosition) < this.imageRowCalc) {
            if (Math.abs(this.imageRowPosition) > (this.imageRowCalc - 39)) {
              this.imageRowPosition = -Math.abs(this.imageRowCalc);
            } else {
              this.imageRowPosition = parseInt(this.imageRowPosition) - 50;
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

			setOption(variant, option) {
				document.querySelector('#variant-input-' + variant).value = option;

				let container = document.querySelector('#variant-container-' + variant);
				let options = container.querySelectorAll('.option.selected');

				for (let i = 0; i < options.length; i++) {
					options[i].classList.remove('selected');
				}

				let target = document.querySelector('#option-' + option);
				target.classList.add('selected');

				let input = container.querySelector('input');
				input.value = option;
			},
    },
  };
</script>
