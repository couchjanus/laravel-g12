<template>
    <div class="container">
       <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form  @submit.prevent="formSubmit">
                        <strong>Comment Here:</strong>
                        <textarea class="form-control" v-model="comment.body"></textarea>
                        <button class="btn btn-success">Submit</button>
                        </form>
                        <strong>Output:</strong>
                        <pre>
                        {{output}}
                        </pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: ["currentId"],
        
        mounted() {
            console.log('Component mounted.');
        },

        data() {
            return {
            comment: {
              body: ""
            },
              output: ''
            };

        },

        methods: {
          formSubmit(e) {
            axios
              .post("http://localhost:8000/post/" + this.currentId + "/comment", this.comment)
              .then(response => {
                this.comment.body = "";
                // this.fetchComments();
              })
              .catch(error => {
                this.comment.body = "";
                console.log('error Data: ', error);
              });
          }
            //   e.preventDefault();
            //         let currentObj = this;

            //         axios({
            //           method: 'post',
            //           url: '/formSubmit',
            //           params: {
            //             body: this.body
            //           }
            //         })
            //         // axios.post('/formSubmit', {
            //         //     // author: this.currentUser.name,
            //         //     body: this.body
            //         // })

            //         .then(function (response) {
            //           console.log('Response Data: ', response.data);
            //           currentObj.output = response.data;
            //         })

            //         .catch(function (error) {
            //             currentObj.output = error;
            //             console.log('error Data: ', error);
            //         });
            //     }
            // }
    }
  }
</script>
