<template>

  <main role="main" class="mx-4">
    <h2 class="mx-2">Add Product</h2>
    <hr>

    <form id="product_form">
      <div class="form-group">
        <label>SKU:</label>
        <input type="text" :class="['form-control my-2', errors.sku ? 'is-invalid' : '']" id="sku" v-model="sku">
        <span class="bg-danger text-white p-1 mt-1 rounded" v-if="errors.sku">
          {{ errors.sku[0] }}
        </span>
      </div>

      <div class="form-group">
        <label>Name:</label>
        <input type="text" :class="['form-control my-2', errors.name ? 'is-invalid' : '']" id="name" v-model="name">
        <span class="bg-danger text-white p-1 mt-1 rounded" v-if="errors.name">
          {{ errors.name[0] }}
        </span>
      </div>

      <div class="form-group">
        <label>Price:</label>
        <input type="number" :class="['form-control my-2', errors.price ? 'is-invalid' : '']" id="price"
               v-model="price">
        <span class="bg-danger text-white p-1 mt-1 rounded" v-if="errors.price">
          {{ errors.price[0] }}
        </span>
      </div>

      <div class="form-group">
        <label>Type Switcher</label>
        <select class="form-control" id="productType" v-model="type">
          <option id="DVD" value="0">DVD</option>
          <option id="Book" value="1">Book</option>
          <option id="Furniture" value="2">Furniture</option>
        </select>
      </div>

      <div :class="['form-control my-2', errors.size ? 'is-invalid' : '']" v-if="type == 0">
        <label>Size (MB):</label>
        <input type="number" class="form-control" id="size" v-model="size">
        <span class="bg-danger text-white p-1 mt-1 rounded" v-if="errors.size">
          {{ errors.size[0] }}
        </span>
        <span>Please, provide size.</span>
      </div>

      <div :class="['form-control my-2', errors.weight ? 'is-invalid' : '']" v-if="type == 1">
        <label>Weight (KG):</label>
        <input type="number" class="form-control" id="weight" v-model="weight">
        <span class="bg-danger text-white p-1 mt-1 rounded" v-if="errors.weight">
          {{ errors.weight[0] }}
        </span>
        <span>Please, provide weight.</span>
      </div>

      <div v-if="type == 2">
        <div :class="['form-control my-2', errors.height ? 'is-invalid' : '']">
          <label>Height (CM):</label>
          <input type="number" class="form-control" id="height" v-model="height">
          <span class="bg-danger text-white p-1 mt-1 rounded" v-if="errors.height">
          {{ errors.height[0] }}
        </span>
        </div>

        <div :class="['form-control my-2', errors.width ? 'is-invalid' : '']">
          <label>Width (CM):</label>
          <input type="number" class="form-control" id="width" v-model="width">
          <span class="bg-danger text-white p-1 mt-1 rounded" v-if="errors.width">
          {{ errors.width[0] }}
        </span>
        </div>

        <div :class="['form-control my-2', errors.length ? 'is-invalid' : '']">
          <label>length (CM):</label>
          <input type="number" class="form-control" id="length" v-model="length">
          <span class="bg-danger text-white p-1 mt-1 rounded" v-if="errors.length">
          {{ errors.length[0] }}
        </span>
        </div>

        <span>Please, provide dimensions.</span>
      </div>
    </form>

    <button class="btn btn-primary mx-2" @click.prevent="addProduct()">Save</button>
    <button class="btn btn-secondary" @click.prevent="cancel()">Cancel</button>

  </main>
</template>

<script>
import axios from "axios";

export default {
  name: 'AddProductView',
  data() {
    return {
      sku: '',
      name: '',
      price: '',
      type: 0,
      size: '',
      weight: '',
      height: '',
      width: '',
      length: '',
      errors: {}
    }
  },
  methods: {
    addProduct() {
      let product = {
        sku: this.sku,
        name: this.name,
        price: this.price,
        type: this.type,
      }

      if (this.type == 0) {
        product.size = this.size;
      } else if (this.type == 1) {
        product.weight = this.weight;
      } else if(this.type == 2) {
        product.height = this.height;
        product.width = this.width;
        product.length = this.length;
      }

      axios.post('/api/products/store', product,
          {
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            }
          }
      )
      .then(response => {
        this.reset();
        this.$router.push('/');
      })
      .catch(error => {
        this.errors = error.response.data;
        console.log(error.response.data);
      });
    },
    reset() {
      this.sku = '';
      this.name = '';
      this.price = '';
      this.size = '';
      this.weight = '';
      this.height = '';
      this.width = '';
      this.length = '';
      this.errors = {};
    },
    cancel() {
      this.reset();
      this.$router.push('/');
    }
  }
}
</script>