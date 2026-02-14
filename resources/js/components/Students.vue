<template>
<div class="card">

    <h1 class="page-title">Estudiantes</h1>

    <h3 class="section-title">{{ editingStudent ? 'Editar estudiante' : 'Nuevo estudiante' }}</h3>
    <form @submit.prevent="saveStudent">

        <input v-model="studentForm.name" placeholder="Nombre" required>
        <input v-model="studentForm.email" placeholder="Email" required>

        <select v-model="studentForm.course_id" required>
            <option disabled value="">Selecciona curso</option>
            <option v-for="c in courses" :key="c.id" :value="c.id">
                {{ c.name }}
            </option>
        </select>

        <button type="submit">
            {{ editingStudent ? 'Actualizar' : 'Crear' }}
        </button>

        <button v-if="editingStudent" type="button" @click="cancelStudentEdit">
            Cancelar
        </button>
    </form>

    <ul v-if="students.length">
        <li v-for="s in students" :key="s.id">
            <div>
                <strong>{{ s.name }}</strong>
                ({{ s.email }}) - Curso: <b>{{ s.course?.name }}</b>
            </div>

        <div class="actions">
            <button @click="editStudent(s)">Editar</button>
            <button type="button" @click="deleteStudent(s.id)">Eliminar</button>
        </div>

        </li>
    </ul>

</div>
</template>


<script>
export default {

data(){
return{
    students:[],
    courses:[],
    editingStudent:false,
    studentId:null,
    studentForm:{name:'',email:'',course_id:''}
}
},

async mounted(){
    await this.loadCourses();
    await this.loadStudents();
},

methods:{
async loadCourses(){
    const res = await fetch('/api/courses');
    this.courses = await res.json();
},

async loadStudents(){
    const res = await fetch('/api/students');
    this.students = await res.json();
},

async saveStudent(){

    const url = this.editingStudent
        ? `/api/students/${this.studentId}`
        : `/api/students`;

    const method = this.editingStudent ? 'PUT' : 'POST';

    const res = await fetch(url,{
        method,
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify(this.studentForm)
    });

    if(!res.ok){
        const error = await res.json();
        alert("Error: " + JSON.stringify(error.errors ?? error));
        return;
    }

    const savedStudent = await res.json();

    // 🔥 actualizar lista en memoria sin recargar API
    if(this.editingStudent){
        const index = this.students.findIndex(s => s.id === savedStudent.id);
        if(index !== -1){
            this.students[index] = savedStudent;
        }
    }else{
        this.students.unshift(savedStudent);
    }

    this.studentForm={name:'',email:'',course_id:''};
    this.editingStudent=false;
},

editStudent(s){
    this.studentForm={name:s.name,email:s.email,course_id:s.course_id};
    this.studentId=s.id;
    this.editingStudent=true;
},

async deleteStudent(id){
    await fetch(`/api/students/${id}`,{method:'DELETE'});
    this.students = this.students.filter(s => s.id !== id);
},

cancelStudentEdit(){
    this.studentForm={name:'',email:'',course_id:''};
    this.editingStudent=false;
}
}
}
</script>
