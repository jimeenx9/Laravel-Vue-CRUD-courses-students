<template>
<div>

    <h1>🎓 Panel académico</h1>

    <nav style="margin-bottom:20px">
        <router-link to="/courses">Cursos</router-link> |
        <router-link to="/students">Estudiantes</router-link>
    </nav>

    <router-view />

</div>
</template>

<script>
export default {
    name: "App"
}
</script>
