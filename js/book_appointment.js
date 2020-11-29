const appointment_form = document.getElementById("appointment_form");
const app_dt_available = document.getElementsByClassName("app_dt_available");
for (btn of app_dt_available) {
    btn.addEventListener("click", function () {
        document.getElementById("error_message").innerHTML = "";
        //console.log(this.getAttribute("data-date") + " " + this.getAttribute("data-time"));
        appointment_form.app_date.value = this.getAttribute("data-date");
        appointment_form.app_time.value = this.getAttribute("data-time");
        document.getElementById("app_dt_view").innerHTML = new Date(
            this.getAttribute("data-date") +
                "T" +
                this.getAttribute("data-time")
        ).toLocaleDateString("en-US", {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "numeric",
            minute: "numeric",
        });
        appointment_form.submit.disabled = false;
    });
}
