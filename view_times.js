window.onload = function() {
    let count = localStorage.getItem("pageViews") || 0;
    count++;
    localStorage.setItem("pageViews", count);
    const viewCountElement = document.getElementById("viewCount");
    if (viewCountElement) {
        viewCountElement.innerText = "访问次数：" + count;
    }
};
