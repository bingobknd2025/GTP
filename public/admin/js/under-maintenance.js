
function updateTimer() {
    future = Date.parse("Dec 19, 2023 11:30:00");
    now = new Date();
    diff = future - now;

    days = Math.floor(diff / (1000 * 60 * 60 * 24));
    hours = Math.floor(diff / (1000 * 60 * 60));
    mins = Math.floor(diff / (1000 * 60));
    secs = Math.floor(diff / 1000);

    d = days;
    h = hours - days * 24;
    m = mins - hours * 60;
    s = secs - mins * 60;

    document.getElementById("timer")
        .innerHTML =
        '<div class="col-xl-3 col-lg-6 col-md-3 col-sm-3 col-6"><div class="p-3 coming-soon-time btn btn-primary bg-primary text-fiexd-white fw-bold d-flex flex-column justify-content-center align-items-center"><p class="mb-1 text-fiexd-white fs-12">DAYS</p><span class="fw-semibold mb-0 fw-bold fs-20">' + d + '</span></div></div>' +
        '<div class="col-xl-3 col-lg-6 col-md-3 col-sm-3 col-6"><div class="p-3 coming-soon-time btn btn-primary bg-primary text-fiexd-white d-flex flex-column justify-content-center align-items-center"><p class="mb-1 fs-12">HOURS</p><span class="fw-semibold mb-0 fw-bold fs-20">' + h + '</span></div></div>' +
        '<div class="col-xl-3 col-lg-6 col-md-3 col-sm-3 col-6"><div class="p-3 coming-soon-time btn btn-primary bg-primary text-fiexd-white d-flex flex-column justify-content-center align-items-center"><p class="mb-1 fs-12">MINUTES</p><span class="fw-semibold mb-0 fw-bold fs-20">' + m + '</span></div></div>' +
        '<div class="col-xl-3 col-lg-6 col-md-3 col-sm-3 col-6"><div class="p-3 coming-soon-time btn btn-primary bg-primary text-fiexd-white d-flex flex-column justify-content-center align-items-center"><p class="mb-1 fs-12">SECONDS</p><span class="fw-semibold mb-0 fw-bold fs-20">' + s + '</span></div></div>'
}
setInterval('updateTimer()', 1000);