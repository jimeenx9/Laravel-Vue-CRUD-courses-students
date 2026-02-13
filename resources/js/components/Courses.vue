<template>
<div>

    <h1>🎓 Cursos</h1>

    <h3>{{ editing ? 'Editar curso' : 'Nuevo curso' }}</h3>
    <form @submit.prevent="saveCourse">
        <input v-model="newCourse.name" placeholder="Nombre" required>
        <input v-model="newCourse.description" placeholder="Descripción">
        <button type="submit">{{ editing ? 'Actualizar' : 'Crear' }}</button>
        <button v-if="editing" type="button" @click="cancelEdit">Cancelar</button>
    </form>

    <ul v-if="courses.length">
        <li v-for="course in courses" :key="course.id">
            <strong>{{ course.name }}</strong>
            <span v-if="course.description"> - {{ course.description }}</span>
            <button @click="editCourse(course)">Editar</button>
            <button @click="deleteCourse(course.id)">Eliminar</button>
        </li>
    </ul>

</div>
</template>

<script>
export default {

data(){
return{
    courses:[],
    editing:false,
    editId:null,
    newCourse:{name:'',description:''}
}
},

async mounted(){
    await this.loadCourses();
},

methods:{
async loadCourses(){
    const res = await fetch('/api/courses');
    this.courses = await res.json();
},

async saveCourse(){
    if(this.editing){
        await fetch(`/api/courses/${this.editId}`,{
            method:'PUT',
            headers:{'Content-Type':'application/json'},
            body:JSON.stringify(this.newCourse)
        });
    }else{
        await fetch('/api/courses',{
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body:JSON.stringify(this.newCourse)
        });
    }

    this.newCourse={name:'',description:''};
    this.editing=false;
    await this.loadCourses();
},

editCourse(c){
    this.newCourse={name:c.name,description:c.description};
    this.editId=c.id;
    this.editing=true;
},

async deleteCourse(id){
    await fetch(`/api/courses/${id}`,{method:'DELETE'});
    await this.loadCourses();
},

cancelEdit(){
    this.newCourse={name:'',description:''};
    this.editing=false;
}
}
}
</script>
