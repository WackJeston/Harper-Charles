<template>
	<!-- Homepage Carousel -->
	<h3>Homepage Carousel<span v-show="this.homepagecarouselcount > 0"> ({{ this.homepagecarouselcount }})</span></h3>

	<a href="/landing-zonesShowZone/1/0" v-if="this.homepagecarouselshow == 1"><button class="pb-success" type="button">On</button></a>
	<a href="/landing-zonesShowZone/1/1" v-else><button class="pb-danger" type="button">Off</button></a>
  <button class="page-button" type="button" :class="{ 'button-active' : addImage == true }"
  @click="addImage == true ? addImage = false : addImage = true">Add Slide</button>

	<form class="web-box" v-show="addImage == true" action="/landing-zonesStoreSlide/1" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="addImage = false"></i>
    <input type="hidden" name="_token" :value="csrf">

		<label for="title">Title</label>
    <input type="text" name="title" maxlength="100">

		<label for="subtitle">Subtitle</label>
    <input type="text" name="subtitle" maxlength="100">

    <label for="image">Image<span> *</span></label>
    <label class="file-input-label" for="image">
      <input @change="this.fileSelected" class="file-input" type="file" name="image" id="image" accept="image/jpg" required>
      <div v-if="!this.files || !this.files.length">No file selected</div>
      <div v-else v-for="file in this.files" :key="file.name">{{file.name}}</div>
    </label>

    <button class="submit" type="submit">Upload</button>
  </form>

	<table class="web-box">
		<tr>
			<th id="column1">#</th>
			<th id="column2">Title</th>
			<th id="column3" class="hide-mobile">Subtitle</th>
			<th id="column4">primary</th>
			<th id="column5" class="tr-button align-right"></th>
		</tr>

		<tr v-for="(slide, i) in this.homepagecarousel">
			<td id="column1"><div>{{ slide.id }}</div></td>
			<td id="column2"><div>{{ slide.title }}</div></td>
			<td id="column3" class="hide-mobile"><div>{{ slide.subtitle }}</div></td>
			<td id="column4">
        <i v-if="slide.primary" class="fa-solid fa-circle-check primary"></i>
        <i v-else class="fa-solid fa-circle-xmark non-primary"></i>
      </td>
			<td id="column5" class="tr-buttons">
				<a :href="'/landing-zonesPrimarySlide/' + slide.id">
          <i class="fa-solid fa-ranking-star">
            <div class="button-label">
              <p>Make Primary</p>
              <div></div>
            </div>
          </i>
        </a>
        <a @click="showImage(slide.fileName)">
          <i class="fa-solid fa-eye">
            <div class="button-label">
              <p>View Image</p>
              <div></div>
            </div>
          </i>
        </a>
        <a :href="'/landing-zonesDeleteSlide/' + slide.id">
          <i class="fa-solid fa-trash-can">
            <div class="button-label">
              <p>Delete</p>
              <div></div>
            </div>
          </i>
        </a>
			</td>
		</tr>

    <div class="image-viewer" v-show="this.imageView">
      <img class="viewer-image">
      <div class="viewer-overlay"></div>
      <i class="fa-solid fa-xmark" @click="closeImage()"></i>
    </div>

		<div v-show="this.homepagecarousel == false" class="empty-table">
			<h3>No Slides</h3>
		</div>
	</table>
</template>

<script>
	export default {
		props: [
			'homepagecarousel',
			'homepagecarouselcount',
			'homepagecarouselshow',
		],

		data() {
			return {
				csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
				show: false,
        addImage: false,
        imageView: false,
        files: null,
			};
		},

		methods: {
			showImage(fileName) {
        const imageZone = document.querySelector('.viewer-image');
        imageZone.src = 'https://template-website-bucket.s3.eu-west-2.amazonaws.com/assets/' + fileName;
        this.imageView = true;
      },

      closeImage() {
        const imageZone = document.querySelector('.viewer-image');
        imageZone.src = '';
        this.imageView = false;
      },

      fileSelected(e) {
        this.files = e.target.files;
      },
		},

		mounted() {
			document.querySelector("#column1").style.width = "10%";
	    document.querySelector("#column2").style.width = "20%";
	   	document.querySelector("#column3").style.width = "40%";
			document.querySelector("#column4").style.width = "15%";
	   	document.querySelector("#column5").style.width = "15%";
  	},
	};
</script>
