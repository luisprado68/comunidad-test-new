console.log("test");
// console.log(window.INIT_MIN_MINUTE_CHATTER);
let stream_active = false;
let actual = new Date();
let hour_actual = actual.getHours();
let times = JSON.parse(document.getElementById("times").value);
console.log(times);
times.forEach((element) => {
    console.log(element);
    if (hour_actual == element) {
        stream_active = true;
    }
});

if (stream_active) {
    const id = setInterval(() => {
        current = new Date();
        var hour = current.getHours();
        var minute = current.getMinutes();
        console.log(hour + ":" + minute);
        // setar y probar un minuto espefico 15 y 50
        // original if (minute >= 50 && minute <= 55 || minute >= 10 && minute <= 15) {
            console.log(window.INIT_MIN_MINUTE_CHATTER);
        if (minute >= window.INIT_MIN_MINUTE_CHATTER && minute <= window.INIT_MAX_MINUTE_CHATTER ||
            minute >= window.SECOND_MIN_MINUTE_CHATTER && minute <= window.SECOND_MAX_MINUTE_CHATTER) {
            console.log("entro");
            clearInterval(id);

            $.ajax({
                url: "chatters",
                type: "GET",

                success: function (response) {
                    console.log(response);

                    if (response.status === "ok") {
                        // $("#edit-dialog").modal("hide");
                        // table.draw();
                        console.log("okkkk");
                        console.log(response);
                        location.reload();
                    } else if (response.status === "error") {
                        console.log("error");
                        // window.alert(response.message);
                        // window.location.href = "{{ route('schedule') }}";
                        location.reload();
                    }
                    // $(".loading").hide();
                },
                error: function (data) {},
            });
        }
    }, 60000);
}
