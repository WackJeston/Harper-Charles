<template>

  <!-- Buttons -->
  <button @click="this.delete()" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
  <a :href="'/category-profileShowCategory/' + this.category.id + '/0'" v-if="this.category.show == 1"><button class="page-button pb-success" type="button"><i class="fa-solid fa-toggle-on"></i>On</button></a>
	<a :href="'/category-profileShowCategory/' + this.category.id + '/1'" v-else><button class="page-button pb-danger" type="button"><i class="fa-solid fa-toggle-off"></i>Off</button></a>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'edit' }"
  @click="show == 'edit' ? show = false : show = 'edit'">Edit</button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'images' }"
  @click="show == 'images' ? show = false : show = 'images'">Images<span v-show="this.imagecount > 0"> ({{ this.imagecount }})</span></button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'products' }"
  @click="show == 'products' ? show = false : show = 'products'">Products<span v-show="this.category.productCount > 0"> ({{ this.category.productCount }})</span></button>

  <!-- Delete Warning -->
  <div @click="this.delete(0)" class="warning-overlay">
    <div class="web-box warning-box dk">
      <h3 class="warning">WARNING</h3>
      <p>This will permanently delete <strong>Category #{{ this.category.id }}</strong></p>
      <div class="row">
        <a :href="'/category-profileDelete/' + this.category.id"><button type="button" name="delete" class="delete">Delete</button></a>
        <button @click="this.delete(0)" type="button" name="cancel" class="cancel">Cancel</button>
      </div>
    </div>
  </div>

  <!-- Edit -->
  <form class="web-box dk" v-show="show == 'edit'" :action="'/category-profileUpdate/' + this.category.id" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="title">Title<span> *</span></label>
    <input type="text" name="title" maxlength="100" :value="this.category.title" required>

    <label for="subtitle">Subtitle</label>
    <input type="text" name="subtitle" maxlength="100" :value="this.category.subtitle">

    <label for="description">Description</label>
    <textarea type="text" name="description" maxlength="1000" :value="this.category.description"></textarea>

    <button class="submit" type="submit">Update</button>
  </form>

  <!-- Images Form -->
  <form class="web-box dk" v-show="show == 'images'" :action="'/category-profileStoreImage/' + this.category.id" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="name">Name<span> *</span></label>
    <input type="text" name="name" maxlength="100" required>

    <label for="image">Image<span> *</span></label>
    <label class="file-input-label" for="image">
      <input @change="this.fileSelected" class="file-input" type="file" name="image" id="image" accept="image/jpg" required>
      <div v-if="!this.files || !this.files.length">No file selected</div>
      <div v-else v-for="file in this.files" :key="file.name">{{file.name}}</div>
    </label>

    <button class="submit" type="submit">Upload</button>
  </form>

  <!-- Images table -->
  <table class="web-box" v-show="show == 'images'">
    <tr>
      <th id="image-column1">#</th>
      <th id="image-column2">Name</th>
      <th id="image-column3">Primary</th>
      <th id="image-column4"></th>
    </tr>

    <tr v-for="(image, i) in this.images">
      <td id="image-column1"><div>{{ image.id }}</div></td>
      <td id="image-column2"><div>{{ image.name }}</div></td>
      <td id="image-column3">
        <div>
          <i v-if="image.primary" class="fa-solid fa-circle-check primary"></i>
          <i v-else class="fa-solid fa-circle-xmark non-primary"></i>
        </div>
      </td>

      <td id="image-column4" class="tr-buttons">
        <a :href="'/category-profilePrimaryImage/' + image.id">
          <i class="fa-solid fa-ranking-star">
            <div class="button-label">
              <p>Make Primary</p>
              <div></div>
            </div>
          </i>
        </a>
        <a @click="showImage(image.fileName)">
          <i class="fa-solid fa-eye">
            <div class="button-label">
              <p>View</p>
              <div></div>
            </div>
          </i>
        </a>
        <a :href="'/category-profileDeleteImage/' + image.id">
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

    <div v-show="this.images == false" class="empty-table">
      <h3>No Images</h3>
    </div>

  </table>


  <!-- Products Form -->
  <form class="web-box" v-show="show == 'products'" :action="'/category-profileAddProduct/' + this.category.id" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="product">Select Product</label>
    <select name="product">
      <option></option>
      <option v-for="(product, i) in this.allproducts" :value="product.id">(#{{ product.id }}) {{ product.title }}</option>
    </select>

    <button class="submit" type="submit">Add</button>
  </form>

  <!-- Products Table -->
  <table class="web-box" v-show="show == 'products'">

    <tr>
      <th id="product-column1">#</th>
      <th id="product-column2">Title</th>
      <th id="product-column3" class="hide-mobile">Subtitle</th>
      <th id="product-column4">Product #</th>
      <th id="product-column5"></th>
    </tr>

    <tr v-for="(product, i) in this.products">
      <td id="product-column1"><div>{{ product.id }}</div></td>
      <td id="product-column2"><div>{{ product.title }}</div></td>
      <td id="product-column3" class="hide-mobile"><div>{{ product.subtitle }}</div></td>
      <td id="product-column4"><div>{{ product.productNumber }}</div></td>
      <td id="product-column5" class="tr-buttons">
        <a :href="'/category-profileRemoveProduct/' + this.category.id + '/' + product.id">
          <i class="fa-solid fa-ban">
            <div class="button-label">
              <p>Remove</p>
              <div></div>
            </div>
          </i>
        </a>
        <a :href="'/admin/product-profile/' + product.id">
          <i class="fa-solid fa-arrow-up-right-from-square">
            <div class="button-label">
              <p>Manage Product</p>
              <div></div>
            </div>
          </i>
        </a>
      </td>
    </tr>

    <div v-show="this.products == false" class="empty-table">
      <h3>No Products</h3>
    </div>

  </table>
</template>


<script>
  export default {
    props: [
      'images',
      'imagecount',
      'category',
      'products',
      'allproducts',
    ],

    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        show: false,
        imageView: false,
        files: null,
      };
    },

    methods: {
      hideMobile() {
        if (window.innerWidth < 650) {
          document.querySelector("#image-column1").style.width = "15%";
          document.querySelector("#image-column2").style.width = "40%";
          document.querySelector("#image-column3").style.width = "20%";
          document.querySelector("#image-column4").style.width = "25%";
        } else {
          document.querySelector("#image-column1").style.width = "10%";
          document.querySelector("#image-column2").style.width = "55%";
          document.querySelector("#image-column3").style.width = "15%";
          document.querySelector("#image-column4").style.width = "20%";
        }

        if (window.innerWidth < 650) {
          document.querySelector("#product-column1").style.width = "10%";
          document.querySelector("#product-column2").style.width = "40%";
          document.querySelector("#product-column4").style.width = "30%";
          document.querySelector("#product-column5").style.width = "20%";
        } else {
          document.querySelector("#product-column1").style.width = "10%";
          document.querySelector("#product-column2").style.width = "30%";
          document.querySelector("#product-column3").style.width = "25%";
          document.querySelector("#product-column4").style.width = "20%";
          document.querySelector("#product-column5").style.width = "15%";
        }
      },

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

      delete(toggle = 1) {
        if (toggle == 1) {
          document.querySelector('.warning-overlay').style.display = 'flex';
        } else {
          document.querySelector('.warning-overlay').style.display = 'none';
        }
      },
    },

    mounted() {
      this.hideMobile();
    },

    created() {
      window.addEventListener('resize', this.hideMobile);
    },
  };
</script>
