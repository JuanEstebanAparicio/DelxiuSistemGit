<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="linkMenuPublico" class="mt-6 bg-gray-50 p-4 border rounded shadow-sm"></div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  fetch("../back_principal/obtener_token_menu.php")
    .then(res => res.json())
    .then(data => {
      if (data.error) return console.error("Error:", data.error);

      const url = `${location.origin}/menu_cliente.php?uid=${data.uid}&token=${data.token}`;

      const input = document.createElement("input");
      input.className = "w-full p-2 border rounded bg-gray-100";
      input.readOnly = true;
      input.value = url;

      const copiarBtn = document.createElement("button");
      copiarBtn.textContent = "Copiar enlace";
      copiarBtn.className = "mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700";
      copiarBtn.onclick = () => {
        navigator.clipboard.writeText(input.value);
        alert("✅ Enlace copiado al portapapeles");
      };

      const wrapper = document.getElementById("linkMenuPublico");
      wrapper.innerHTML = "<h3 class='font-semibold mb-2'>🔗 Enlace público del menú:</h3>";
      wrapper.appendChild(input);
      wrapper.appendChild(copiarBtn);
    });
});
</script>

</body>
</html>