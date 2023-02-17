<template>
    <main role="main">

      <div class="row">
        <div class="col d-flex justify-content-between align-items-center">
          <h2 class="mx-2">Product List</h2>
          <div>
            <router-link class="btn btn-primary mx-2" to="/addproduct">ADD</router-link>
            <button class="btn btn-danger mx-2" id="delete-product-btn" @click="deleteProducts()">MASS DELETE</button>
          </div>
        </div>

      </div>

      <hr>

      <div class="py-5">
        <div class="container">

          <div class="row">

            <div class="col-md-3" v-for="product in products">
              <div class="card mb-4 box-shadow">
                <div class="card-body p-4">

                  <input type="checkbox" :data-id="product.id" class="delete-checkbox" name="product">

                  <p class="card-text text-center mb-0">
                    {{ product.sku }}
                  </p>

                  <p class="card-text text-center mb-0">
                    {{ product.name }}
                  </p>

                  <p class="card-text text-center mb-0">
                    {{ product.price }}
                  </p>

                  <p class="card-text text-center mb-0">
                    {{ productSpecialAttribute(product) }}
                  </p>

                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </main>
</template>

<script>
import axios from "axios";

export default {
  name: 'HomeView',
  data() {
    return {
      products: []
    }
  },
  created() {
    this.getProducts()
  },
  methods: {
    getProducts() {
      axios.get('/api/products')
          .then(response => {
            this.products = response.data;
          })
    },
    productSpecialAttribute(product) {
      switch (product.type) {
        case 0: // dvd
          console.log(product.size)
          return `Size: ${product.size} MB`;
        case 1: // book
          return `Weight: ${product.weight} KG`
        case 2: // furniture
          return `Dimension: ${product.height}X${product.width}X${product.length} CM`
      }
    },
    deleteProducts() {
      let checkedProducts = []
      let checkboxes = document.querySelectorAll('.delete-checkbox')
      checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
          checkedProducts.push(checkbox.getAttribute("data-id"))
        }
      })

      axios.post('/api/products/delete', {
        products: checkedProducts
      }, {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      })
      .then(response => {
        this.getProducts()
      })
    }
  }
}
</script>
