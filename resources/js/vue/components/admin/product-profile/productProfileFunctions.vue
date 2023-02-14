<template>

  <!-- Buttons -->
  <button @click="this.delete()" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'edit' }"
  @click="show == 'edit' ? show = false : show = 'edit'">Edit</button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'images' }"
  @click="show == 'images' ? show = false : show = 'images'">Images<span v-show="this.imagecount > 0"> ({{ this.imagecount }})</span></button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'categories' }"
  @click="show == 'categories' ? show = false : show = 'categories'">Categories<span v-show="this.product.categoryCount > 0"> ({{ this.product.categoryCount }})</span></button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'variants' }"
  @click="show == 'variants' ? show = false : show = 'variants'">Variants<span v-show="this.variants.length > 0"> ({{ this.variants.length }})</span></button>


  <!-- Delete Warning -->
  <div @click="this.delete(0)" class="warning-overlay">
    <div class="web-box warning-box dk">
      <h3 class="warning">WARNING</h3>
      <p>This will permanently delete <strong>Product #{{ this.product.id }}</strong></p>
      <div class="row">
        <a :href="'/product-profileDelete/' + this.product.id"><button type="button" name="delete" class="delete">Delete</button></a>
        <button @click="this.delete(0)" type="button" name="cancel" class="cancel">Cancel</button>
      </div>
    </div>
  </div>

  <!-- Edit -->
  <form class="web-box dk" v-show="show == 'edit'" :action="'/product-profileUpdate/' + this.product.id" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="title">Title<span> *</span></label>
    <input type="text" name="title" maxlength="100" :value="this.product.title" required>

    <label for="subtitle">Subtitle</label>
    <input type="text" name="subtitle" maxlength="100" :value="this.product.subtitle">

    <label for="description">Description</label>
    <textarea type="text" name="description" maxlength="1000" :value="this.product.description"></textarea>

    <label for="productnumber">Product Number<span> *</span></label>
    <input type="text" name="productnumber" maxlength="100" :value="this.product.productNumber" required>

    <label for="price">Price (£)<span> *</span></label>
    <input type="number" min="0" step="any" name="price" maxlength="100" :value="this.product.price" required>

    <button class="submit" type="submit">Update</button>
  </form>


  <!-- Images Form -->
  <form class="web-box dk" v-show="show == 'images'" :action="'/product-profileStoreImage/' + this.product.id" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="name">Name<span> *</span></label>
    <input type="text" name="name" maxlength="50" required>

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
      <th id="image-column2">Title</th>
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
        <a :href="'/product-profilePrimaryImage/' + image.id">
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
        <a :href="'/product-profileDeleteImage/' + image.id">
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


  <!-- Categories Form -->
  <form class="web-box" v-show="show == 'categories'" :action="'/product-profileAddCategory/' + this.product.id" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="category">Select Category</label>
    <select name="category">
      <option></option>
      <option v-for="(category, i) in this.allcategories" :value="category.id">(#{{ category.id }}) {{ category.title }}</option>
    </select>

    <button class="submit" type="submit">Add</button>
  </form>

  <!-- Categories Table -->
  <table class="web-box" v-show="show == 'categories'">
    <tr>
      <th id="category-column1">#</th>
      <th id="category-column2">Title</th>
      <th id="category-column3">Subtitle</th>
      <th id="category-column4"></th>
    </tr>

    <tr v-for="(category, i) in this.categories">
      <td id="category-column1"><div>{{ category.id }}</div></td>
      <td id="category-column2"><div>{{ category.title }}</div></td>
      <td id="category-column3"><div>{{ category.subtitle }}</div></td>
      <td id="category-column4" class="tr-buttons">
        <a :href="'/product-profileRemoveCategory/' + this.product.id + '/' + category.id">
          <i class="fa-solid fa-ban">
            <div class="button-label">
              <p>Remove</p>
              <div></div>
            </div>
          </i>
        </a>
        <a :href="'/admin/category-profile/' + category.id">
          <i class="fa-solid fa-arrow-up-right-from-square">
            <div class="button-label">
              <p>Manage Category</p>
              <div></div>
            </div>
          </i>
        </a>
      </td>
    </tr>

    <div v-show="this.categories == false" class="empty-table">
      <h3>No Categories</h3>
    </div>
  </table>


  <!-- Variants Form -->
  <form class="web-box" v-show="show == 'variants'" :action="'/product-profileAddVariant/' + this.product.id" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="variant">Select Option</label>
    <select name="variant">
      <option></option>

      <optgroup v-for="(variant, i) in this.allvariants" :label="variant[1]">
        <option v-for="(option, i) in variant[2]" :value="option[0]">{{ option[1] }}</option>
      </optgroup>
    </select>

    <button class="submit" type="submit">Add</button>
  </form>

  <!-- Variants Table -->
  <table class="web-box" v-show="show == 'variants'">
    <tr>
      <th id="product-column1">#</th>
      <th id="product-column2">Option</th>
      <th id="product-column3">Variant</th>
      <th id="product-column4"></th>
    </tr>

    <tr v-for="(variant, i) in this.variants">
      <td id="variant-column1"><div>{{ variant.id }}</div></td>
      <td id="variant-column2"><div>{{ variant.title }}</div></td>
      <td id="variant-column3"><div>{{ variant.parent }}</div></td>
      <td id="variant-column4" class="tr-buttons">
        <a :href="'/product-profileRemoveVariant/' + this.product.id + '/' + variant.id">
          <i class="fa-solid fa-ban">
            <div class="button-label">
              <p>Remove</p>
              <div></div>
            </div>
          </i>
        </a>
      </td>
    </tr>

    <div v-show="this.variants == false" class="empty-table">
      <h3>No Variants</h3>
    </div>
  </table>
</template>


<script>
  export default {
    props: [
      'product',
      'images',
      'imagecount',
      'categories',
      'allcategories',
      'variants',
      'allvariants',
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
      showImage(fileName) {
        const imageZone = document.querySelector('.viewer-image');
        imageZone.src = 'https://hc-main.s3.eu-west-2.amazonaws.com/assets/' + fileName;
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
      if (this.categories.length > 0) {
        document.querySelector("#category-column1").style.width = "15%";
        document.querySelector("#category-column2").style.width = "30%";
        document.querySelector("#category-column3").style.width = "40%";
        document.querySelector("#category-column4").style.width = "15%";
      }

      if (this.images.length > 0) {
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
      }

      if (this.variants.length > 0) {
        document.querySelector("#variant-column1").style.width = "15%";
        document.querySelector("#variant-column2").style.width = "35%";
        document.querySelector("#variant-column3").style.width = "35%";
        document.querySelector("#variant-column4").style.width = "15%";
      }
    },
  };
</script>
