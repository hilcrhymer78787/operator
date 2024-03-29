const MINUTE = [
    { txt: "3分", val: 3 },
    { txt: "5分", val: 5 },
    { txt: "7分", val: 7 },
    { txt: "10分", val: 10 },
    { txt: "15分", val: 15 },
    { txt: "20分", val: 20 },
    { txt: "25分", val: 25 },
    { txt: "30分", val: 30 },
    { txt: "35分", val: 35 },
    { txt: "40分", val: 40 },
    { txt: "45分", val: 45 },
    { txt: "50分", val: 50 },
    { txt: "55分", val: 55 },
    { txt: "60分", val: 60 },
    { txt: "1時間30分", val: 90 },
    { txt: "2時間", val: 120 },
    { txt: "2時間30分", val: 150 },
    { txt: "3時間", val: 180 },
    { txt: "3時間30分", val: 210 },
    { txt: "4時間", val: 240 },
    { txt: "4時間30分", val: 270 },
    { txt: "5時間", val: 300 },
    { txt: "5時間30分", val: 330 },
    { txt: "6時間", val: 360 },
    { txt: "6時間30分", val: 390 },
    { txt: "7時間", val: 420 },
    { txt: "7時間30分", val: 450 },
    { txt: "8時間", val: 480 },
    { txt: "9時間", val: 540 },
    { txt: "10時間", val: 600 },
    { txt: "11時間", val: 660 },
    { txt: "12時間", val: 720 },
    { txt: "13時間", val: 780 },
    { txt: "14時間", val: 840 },
    { txt: "15時間", val: 900 },
    { txt: "16時間", val: 960 },
    { txt: "17時間", val: 1020 },
    { txt: "18時間", val: 1080 },
    { txt: "19時間", val: 1140 },
    { txt: "20時間", val: 1200 },
    { txt: "21時間", val: 1260 },
    { txt: "22時間", val: 1320 },
    { txt: "23時間", val: 1380 },
    { txt: "24時間", val: 1440 },
]
const TASK_STATE = [
    { txt: "不要", val: null },
    { txt: "未完了", val: 1 },
    { txt: "完了", val: 2 },
    { txt: "遅完了", val: 3 },
]

export default (context, inject) => {
    inject('MINUTE', MINUTE)
    inject('TASK_STATE', TASK_STATE)
}