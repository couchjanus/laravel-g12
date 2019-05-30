<template>
  <div class="card my-4">
    <span v-if="currentUser!==undefined">
      <h5 class="card-header">Leave a Comment:</h5>
      <div class="card-body">
        <form>
          <div class="input-group form-group">
            <textarea
              name="body"
              v-model="comment.body"
              ref="textarea"
              class="form-control"
              rows="3"
            ></textarea>
          </div>
          <div class="input-group">
            <button class="btn btn-success" @click.prevent="createComment">Add Comment</button>
          </div>
        </form>
      </div>
    </span>
    <!-- Single Comment -->
    <h4>Comments</h4>
    <ul class="list-group list-group-flush">
      <li class="list-group-item" v-for="comment in comments" v-bind:key="comment.id">
        <h3>{{comment.creator.name}} </h3>  <time>{{comment.created_at}}</time>
        <div class="media-body">{{comment.body}}</div>
      </li>
    </ul>
    
  </div>
</template>

<script>
export default {
  props: ["currentId", "currentUser"],

  data: () => ({
    comments: [],
    comment: {
      body: ""
    },
    errors: []
  }),

  created: function() {
    this.fetchComments();
  },

  methods: {
    fetchComments: function() {
      axios
        .get("/api/post/" + this.currentId + "/comments")
        .then(response => {
          this.comments = response.data;
        })
        .catch(error => {
          this.errors.push(error);
        });
    },
  
    createComment: function() {

      const axiosConfig = {
          headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
              'Access-Control-Allow-Origin': '*',
          }
      };
      
      const resData = { 
          comment: this.comment,
          post_id: this.currentId,
          user_id: this.currentUser
      };
      axios.post('/api/comment', resData, axiosConfig)
      .then(response => {
            if(response.status == 200){
                console.log("All successfull");
                this.comment.body = "";
                this.fetchComments();
            } else {
                console.log(response.status);
                this.comment.body = "";
                this.fetchComments();
            }
        })
      .catch(error => {
          // this.errors.push(error);
          console.log(error);
          this.comment.body = "";
          this.fetchComments();
      });
    }
  }
};
</script>
