// Obtener la ventana de alerta
var modal = document.getElementById("alert");

// Obtenemos el boton de close
var span = document.getElementsByClassName("close")[0];

// cuando el usuario de click en close, cerrar la alerta
span.onclick = function () {
  modal.style.display = "none";
};
