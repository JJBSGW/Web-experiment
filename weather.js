// 异步函数获取天气信息
async function getWeather(adcode) {
    const key = 'your key'; // 替换为你的高德API Key
    const url = `https://restapi.amap.com/v3/weather/weatherInfo?city=${adcode}&key=${key}`;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('网络响应不是OK状态');
        }
        const data = await response.json();
        if (data.status === '1' && data.lives) {
            const weatherInfo = data.lives[0];
            const weatherElement = document.getElementById('weather');
            if (weatherElement) {
                weatherElement.innerHTML = `<h2>江西抚州</h2>
                    <strong>天气现象：</strong>${weatherInfo.weather}<br>
                    <strong>温度：</strong>${weatherInfo.temperature}℃<br>
                    <strong>风向：</strong>${weatherInfo.winddirection}<br>
                    <strong>风力：</strong>${weatherInfo.windpower}级<br>
                    <strong>空气湿度：</strong>${weatherInfo.humidity}%<br>
                `;
            } else {
                console.error('未找到ID为weather的DOM元素');
            }
        } else {
            console.error('API返回错误状态:', data.info);
            document.getElementById('weather').textContent = '获取天气信息失败1';
        }
    } catch (error) {
        console.error('获取天气信息失败:', error);
        document.getElementById('weather').textContent = '获取天气信息失败2';
    }
}

// 长沙的adcode
const homeAdcode = '361002';
getWeather(homeAdcode);