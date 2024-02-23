<script>
export default {
  name: "user"
}
</script>

<template>
  <h1>Welcome, {{ session['user'] }}!</h1>
  <a id="logout" href="/logout">Logout</a>
  <table>
    <thead>
    <tr>
      <th>Session User</th>
      <th>Session CSRF</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>{{ session['user'] }}</td>
      <td>{{ session['csrf'] }}</td>
    </tr>
    </tbody>
  </table>
</template>

<style scoped>

</style>