<template>
  <div>
    <div class="container mt-4">
      <div class="card" style="background-color:lightgray;">
        <div class="card-body">
          <div class="card-title"> <h5 style=" font-family: 'Comic Sans MS', cursive;
                    font-weight: normal;
                    font-size: 24px;
                    text-transform: uppercase;">Leçon n° {{ lesson.id }}</h5></div>
          <hr class="dotted">
          <p class="card-text"><span style=" font-family: 'Comic Sans MS', cursive;  font-weight: normal;text-transform: uppercase;">Titre</span> : <strong>{{ lesson.title}}</strong></p>
          <p class="card-text"><span style=" font-family: 'Comic Sans MS', cursive;  font-weight: normal;text-transform: uppercase;">Date de début </span>: <strong>{{ lesson.startAt}}</strong></p>
          <p class="card-text"><span style=" font-family: 'Comic Sans MS', cursive;  font-weight: normal;text-transform: uppercase;">Date de fin</span> : <strong>{{ lesson.endAt}}</strong></p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const slug = window.location.href.replace("http://127.0.0.1:8000/api/lesson/","");
const baseUrl = "http://127.0.0.1:8000/api/lessons/" + slug;

export default {
  name : 'Show',
  data: () => ({
    lesson:[],
    isLoading: true
  }),
  mounted () {
    this.getLesson();
  },
  methods: {
    async getLesson() {
        await fetch(baseUrl)
        .then(async (response) => await response.json())
        .then((response) => this.lesson = response["hydra:member"])
        .then(() => console.log(this.lesson))
        .catch((err) => {
          console.log(err);
        });
    },

    setIsLoading() {
      this.isLoading = !this.isLoading;
    }
  }
}
</script>


<style>

</style>
