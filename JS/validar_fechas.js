  document.addEventListener("DOMContentLoaded", () => {
   const hoy = new Date().toISOString().split("T")[0];
  document.getElementById("fecha_ingreso").value = hoy;
  });

  function validarFechas() {
    const ingreso = document.getElementById("fecha_ingreso").value;
    const vencimiento = document.getElementById("fecha_vencimiento").value;

    if (vencimiento && vencimiento < ingreso) {
      alert("La fecha de vencimiento no puede ser anterior a la fecha de ingreso.");
      return false;
    }

    return true;
  }