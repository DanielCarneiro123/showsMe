new Swiper("#event-swiper",{
    slidesPerView: 1.5,
    centeredSlides: true,
    lazyLoading: true,
    loop: false,
    keyboard: {
        enabled: true
    },
    navigation: {
        nextEl: "#nav-right",
        prevEl: "#nav-left"
    },
    pagination: {
        el: ("#event-swiper .swiper-custom-pagination"),
        clickable: true,
        renderBullet: function (index, className) {
        return `<div class=${className}>
            <span class="number">${index + 1}</span>
            <span class="line"></span>
            </div>`;
        }
    }
});