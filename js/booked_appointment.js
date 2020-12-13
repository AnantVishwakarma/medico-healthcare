const btnCancelAppointment = document.getElementById("cancel-appointment");
const btnAbortCancellation = document.getElementById("abort-cancellation");


const modal = document.getElementById("myModal");

btnCancelAppointment.addEventListener("click", function () {
    modal.style.display = "block";
});

btnAbortCancellation.addEventListener("click", function () {
    modal.style.display = "none";
});
