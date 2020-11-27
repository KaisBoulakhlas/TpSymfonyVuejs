<template>
  <div>
    <div class="container mt-4">
      <h1>Liste des leçons</h1>
      <hr class="dotted"/>

      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Voir la leçon</th>
            <th>Titre</th>
            <th>Professeur</th>
            <th>Date de début</th>
            <th>Date de fin</th>
          </tr>
        </thead>
        <tbody v-if="!isLoading">
            <tr v-for="lesson in lessons" v-bind="{ id: lesson.id}">
              <td><a :href="`http://localhost:8000/lesson/${lesson.slug}`"  class="btn btn-outline-primary" style="font-size:1px;margin-left:20px;"><i class="material-icons" >search</i></a></td>
              <td>{{ lesson.title }}</td>
              <td>{{ lesson.teacher }}</td>
              <td>{{ lesson.startAt | formatDate}}</td>
              <td>{{ lesson.endAt | formatDate}}</td>
            </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="7" style="text-align:center">
              <button class="btn btn-primary" type="button" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
const baseUrl = "http://127.0.0.1:8000/api/";

function buildUrl (url) {
  return baseUrl + url
}
export default {
  props: ['lesson'],
  name : 'App',
  data: () => ({
       lessons:[],
        isLoading: true
  }),
  mounted () {
    this.getLessons('lessons');
  },
  methods: {
    async getLessons(section) {
      let url = buildUrl(section);
      await fetch(url)
      .then(async (response) => await response.json())
      .then((response) => this.lessons = response["hydra:member"])
      .then(() => this.setIsLoading())
      .then(() => console.log(this.lessons))
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
