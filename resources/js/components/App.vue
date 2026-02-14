<template>
<div class="layout">

    <aside class="sidebar">
        <div class="logo">🎓 Academic</div>

        <nav class="nav">
            <router-link to="/courses">Cursos</router-link>
            <router-link to="/students">Estudiantes</router-link>
        </nav>
    </aside>

    <main class="main">
        <router-view />
    </main>

</div>
</template>

<script>
export default {
    name: "App"
}
</script>
