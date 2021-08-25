<template>
     <div class="container-fluid bg-dark mb-3">
         <div class="container">
             <nav class="navbar navbar-dark">
                 <span class="navbar-brand mb-0 h1">Timetable Searcher</span>
                 <div>
                    <router-link v-bind:to="{name: 'timetable.list'}">
                     <button class="btn btn-success">Reload</button>
                    </router-link>
                     <button class="btn btn-success">ADD</button>
                 </div>
             </nav>
         </div>
     </div>
 </template>
 
 <script>
     export default {}
 </script>