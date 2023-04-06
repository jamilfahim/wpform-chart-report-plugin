/*******************************************************
 * /                Particular test submission           * /
 ********************************************************/

function array_unique(array) {
   return array.filter(function (el, index, arr) {
      return index == arr.indexOf(el);
   });
}

function getCookie(name) {
   // Split cookie string and get all individual name=value pairs in an array
   var cookieArr = document.cookie.split(";");

   // Loop through the array elements
   for (var i = 0; i < cookieArr.length; i++) {
      var cookiePair = cookieArr[i].split("=");

      /* Removing whitespace at the beginning of the cookie name
      and compare it with the given string */
      if (name == cookiePair[0].trim()) {
         // Decode the cookie value and return
         return decodeURIComponent(cookiePair[1]);
      }
   }

   // Return null if not found
   return null;
}

var homeurl = softvar.home_url;

console.log(softvar);

let names = [];
let submissions = [];
let testdata = [];
let alluser = [];
let alluser_test_count = [];
let user_test_trend = [];

jQuery.ajax({
   url: homeurl + "/wp-json/softtechit/v1/tests",
   method: "GET",
   timeout: 0,
   async: false,
   success: function (result) {
      names = result;
   },
});

jQuery.ajax({
   url: homeurl + "/wp-json/softtechit/v1/submissions",
   method: "GET",
   timeout: 0,
   async: false,
   success: function (result) {
      submissions = result;
   },
});

jQuery.ajax({
   url:
      homeurl +
      "/wp-json/softtechit/v1/submissions/user/" +
      getCookie("current_user"),
   method: "GET",
   timeout: 0,
   async: false,
   success: function (result) {
      testdata = result;
   },
});

jQuery.ajax({
   url: homeurl + "/wp-json/softtechit/v1/submissions/alluser",
   method: "GET",
   timeout: 0,
   async: false,
   success: function (result) {
      alluser = result;
   },
});

jQuery.ajax({
   url: homeurl + "/wp-json/softtechit/v1/submissions/alluser/data",
   method: "GET",
   timeout: 0,
   async: false,
   success: function (result) {
      alluser_test_count = result;
   },
});

jQuery.ajax({
   url:
      homeurl +
      "/wp-json/softtechit/v1/submissions/user/test/" +
      getCookie("current_user"),
   method: "GET",
   timeout: 0,
   async: false,
   success: function (result) {
      user_test_trend = result;
   },
});

// jQuery.ajax({
//    url: homeurl + "/wp-json/softtechit/v1/submissions/data",
//    method: "GET",
//    timeout: 0,
//    async: false,
//    success: function (result) {
//       submission_data = result;
//    },
// });
// var obj = JSON.parse(submission_data);
// console.log(obj)

let submission_length = submissions.length;
let khali_submission = [];

for (i = 0; i < submission_length; i++) {
   if (submissions[i].user_id == getCookie("current_user")) {
      khali_submission.push(submissions[i]);
   }
}

let testnames = [];

for (i = 0; i < khali_submission.length; i++) {
   for (j = 0; j < names.length; j++) {
      if (khali_submission[i].form_id == names[j].id) {
         testnames.push(names[j].name);
      }
   }
}

/*******************************************************
 * /            Api For date range picker         * /
 ********************************************************/
let namesbydate = [];
let submissionsByDate = [];
let testdataByDate = [];
let alluserbydate = [];
let alluser_test_count_bydate = [];
let user_test_trend_by_date = [];

let fromDate = jQuery(".from-date").val();
let toDate = jQuery(".to-date").val();
let selected_user = jQuery(".select-user").val();
let submit = jQuery(".filter-report").val();

//get submission data by date range
jQuery("#filter-report").click(function (e) {
   // Get the value from input
   fromDate = jQuery(".from-date").val();
   toDate = jQuery(".to-date").val();
   selected_user = jQuery(".select-user").val();
   submit = jQuery(".filter-report").val();
   
});

// jQuery.ajax({
//    url: homeurl + "/wp-json/softtechit/v1/tests/",
//    method: "GET",
//    timeout: 60,
//    async: false,
//    success: function (result) {
//       namesbydate = result;
//    },
// });

//alert(namesbydate);
jQuery.ajax({
   url:
      homeurl +
      "/wp-json/softtechit/v1/submissions/by/date/" +
      fromDate +
      "/" +
      toDate,
   method: "GET",
   timeout: 60,
   async: false,
   success: function (result) {
      submissionsByDate = result;
   },
});

jQuery.ajax({
   url: homeurl + "/wp-json/softtechit/v1/submissions/user/by/date/" + selected_user + "/" + fromDate +"/" + toDate,
   method: "GET",
   timeout: 60,
   async: false,
   success: function (result) {
      testdataByDate = result;
   },
});

jQuery.ajax({
   url:
      homeurl +
      "/wp-json/softtechit/v1/submissions/alluser/by/date/" +
      fromDate +
      "/" +
      toDate,
   method: "GET",
   timeout: 60,
   async: false,
   success: function (result) {
      alluserbydate = result;
   },
});

jQuery.ajax({
   url:
      homeurl +
      "/wp-json/softtechit/v1/submissions/alluser/data/by/date/" +
      fromDate +
      "/" +
      toDate,
   method: "GET",
   timeout: 60,
   async: false,
   success: function (result) {
      alluser_test_count_bydate = result;
      //console.log(alluserdatabydate);
   },
});

jQuery.ajax({
   url:
      homeurl +
      "/wp-json/softtechit/v1/submissions/user/test/by/date/" +
      selected_user +
      "/" +
      fromDate +
      "/" +
      toDate,
   method: "GET",
   timeout: 60,
   async: false,
   success: function (result) {
      user_test_trend_by_date = result;
   },
});

let submission_length_by_date = submissionsByDate.length;
let khali_submission_by_date = [];

for (i = 0; i < submission_length_by_date; i++) {
   if (submissionsByDate[i].user_id == selected_user) {
      khali_submission_by_date.push(submissionsByDate[i]);
   }
}

let testnamesbydate = [];

for (i = 0; i < khali_submission_by_date.length; i++) {
   for (j = 0; j < names.length; j++) {
      if (khali_submission_by_date[i].form_id == names[j].id) {
         testnamesbydate.push(names[j].name);
      }
   }
}

if (fromDate && toDate && submit == "filter") {
   var charttestdata = testdataByDate;
   var charttestnames = testnamesbydate;
} else {
   var charttestdata = testdata;
   var charttestnames = testnames;
}

const testchart = document.getElementById("testChart").getContext("2d");

const testChart = new Chart(testchart, {
   type: "pie",
   data: {
      labels: array_unique(charttestnames),
      datasets: [
         {
            label: "Overall Test",
            data: charttestdata,
            backgroundColor: [
               "#4dc9f6",
               "#f67019",
               "#f53794",
               "#537bc4",
               "#acc236",
               "#166a8f",
               "#00a950",
               "#58595b",
               "#8549ba",
            ],
         },
      ],
   },
   options: {
      layout: {
        padding: {
          bottom: 25
        }
      },
      plugins: {
         legend: {
            labels: {
              color: "#000000",  // not 'fontColor:' anymore
              // fontSize: 18  // not 'fontSize:' anymore
              font: {
                size: 12 // 'size' now within object 'font {}'
              }
            }
          },
        tooltip: {
          enabled: true,
          callbacks: {
            footer: (ttItem) => {
              let sum = 0;
              let dataArr = ttItem[0].dataset.data;
              dataArr.map(data => {
                sum += Number(data);
              });
  
              let percentage = (ttItem[0].parsed * 100 / sum).toFixed(2) + '%';
              return `Percentage of data: ${percentage}`;
            }
          }
        },
        /** Imported from a question linked above. 
            Apparently Works for ChartJS V2 **/
        datalabels: {
          formatter: (value, dnct1) => {
            let sum = 0;
            let dataArr = dnct1.chart.data.datasets[0].data;
            dataArr.map(data => {
              sum += Number(data);
            });
  
            let percentage = (value * 100 / sum).toFixed(2) + '%';
            return percentage;
          },
          color: '#fff',
        }
      }
    },
    plugins: [ChartDataLabels]


});

//download chart as a pdf
document.getElementById("testChartbtn").addEventListener("click", downloadPDF);
//donwload pdf from original canvas
function downloadPDF() {

   // var canvaslogo = document.querySelector(".logo");
   var canvas = document.querySelector("#testChart");
  

  // var canvaslogoImg = canvaslogo.toDataURL("image/png", 1.0);
   var canvasImg = canvas.toDataURL("image/png", 1.0);
   
   
   var doc = new jsPDF('landscape');
   doc.setFontSize(18);
  
   
   doc.setFillColor(243, 243, 243);
   doc.rect(0, 0, 1080, 600, "F");

   //doc.addImage(canvaslogoImg, "PNG", 80, 20);

   doc.addImage(canvasImg, "PNG", 80, 40, 130, 130);
   doc.text(125, 200, "proficiensy.com");
  
   doc.save("particular-test.pdf");
}

/*******************************************************
 * /               Particular Test score              * /
 ********************************************************/
let testscore = [];

for (i = 0; i < khali_submission.length; i++) {
   for (j = 0; j < names.length; j++) {
      if (khali_submission[i].form_id == names[j].id) {
         testscore.push(khali_submission[i].score);
      }
   }
}

let testscorebydate = [];

for (i = 0; i < khali_submission_by_date.length; i++) {
   for (j = 0; j < names.length; j++) {
      if (khali_submission_by_date[i].form_id == names[j].id) {
         testscorebydate.push(khali_submission_by_date[i].score);
      }
   }
}

//Final chat test data
if (fromDate && toDate  && submit == "test-score-filter") {
   var charttestscore = testscorebydate;
   var charttestnames = testnamesbydate;
} else {
   var charttestscore = testscore;
   var charttestnames = testnames;
}

// Register the plugin to all charts:
//Chart.register(ChartDataLabels);

const scorechat = document.getElementById("scoreChart").getContext("2d");


const scoreChart = new Chart(scorechat, {
   type: "bar",
   data: {
      labels: charttestnames,
      datasets: [
         {
            label: "Score",
            data: charttestscore,
            backgroundColor: [
               "#4dc9f6",
               "#f67019",
               "#f53794",
               "#537bc4",
               "#acc236",
               "#166a8f",
               "#00a950",
               "#58595b",
               "#8549ba",
            ],
         },
      ],
   },
  
  
 
  
});

//download chart as a pdf
document
   .getElementById("scoreChartBtn")
   .addEventListener("click", testScorePDF);
//donwload pdf from original canvas
function testScorePDF() {
   var canvas = document.querySelector("#scoreChart");
   var canvasImg = canvas.toDataURL("image/png", 1.0);

   var doc = new jsPDF('landscape');
   doc.setFontSize(18);
   
   doc.setFillColor(243, 243, 243);
   doc.rect(0, 0, 1080, 600, "F");

   doc.addImage(canvasImg, "PNG", 70, 50 ,160, 90);
   doc.text(125, 200, "proficiensy.com");
   doc.save("score-test.pdf");
}

/*******************************************************
 * /  Trend test for a particular account    / *
 ********************************************************/
//  const arr = [];
//  const data = khali_submission[i].score;
//  const obj1 = {
//     label: 'Alice',
//     data: data,
// };
//  //const obj2 = {score: '20'};

// for (i = 0; i < khali_submission.length; i++) {
//    for (j = 0; j < names.length; j++) {
//       if (khali_submission[i].form_id == names[j].id) {
//          testname = names[j].name;
//       }
//    }
// }

if (fromDate && toDate) {
   var chart_user_test_trend = user_test_trend_by_date;
} else {
   var chart_user_test_trend = user_test_trend;
}

const trendtestchart = document
   .getElementById("trendTestChart")
   .getContext("2d");
const trendTestChart = new Chart(trendtestchart, {
   type: "line",
   data: {
      labels: ["", "", "", "", "", ""],
      datasets: chart_user_test_trend,
   },
   options: {
      scales: {
         y: {
            type: "linear",
            display: true,
            position: "right",
         },
      },
   },
});

//download chart as a pdf
document
   .getElementById("trendTestChartBtn")
   .addEventListener("click", trendTestChartPDF);
//donwload pdf from original canvas
function trendTestChartPDF() {
   var canvas = document.querySelector("#trendTestChart");
   var canvasImg = canvas.toDataURL("image/png", 1.0);
   
   var doc = new jsPDF('landscape');
   doc.setFontSize(18);
   
   doc.setFillColor(243, 243, 243);
   doc.rect(0, 0, 1080, 600, "F");

   doc.addImage(canvasImg, "PNG",  70, 50 ,160, 90);
   doc.text(125, 200, "proficiensy.com");
   doc.save("trend-test.pdf");
}

/*******************************************************
 * /        All test taken for all account             / *
 ********************************************************/

//Final chat test data
if (fromDate && toDate) {
   var chartalluser = alluserbydate;
   var chartallusertestcount = alluser_test_count_bydate;
} else {
   var chartalluser = alluser;
   var chartallusertestcount = alluser_test_count;
}

const alltestchart = document.getElementById("allTestChart").getContext("2d");

const allTestChart = new Chart(alltestchart, {
   type: "line",
   data: {
      labels: chartalluser,
      datasets: [
         {
            label: " Test taken",
            data: chartallusertestcount,
            borderColor: ["#f67019"],
            backgroundColor: ["#f67019"],
         },
      ],
   },
   options: {
      scales: {
         y: {
            type: "linear",
            display: true,
            position: "right",
         },
      },
   },
});

//download chart as a pdf
document
   .getElementById("allTestChartBtn")
   .addEventListener("click", allTestChartPDF);
//donwload pdf from original canvas
function allTestChartPDF() {
   var canvas = document.querySelector("#allTestChart");
   var canvasImg = canvas.toDataURL("image/png", 1.0);
   
   var doc = new jsPDF('landscape');
   doc.setFontSize(18);
   
   doc.setFillColor(243, 243, 243);
   doc.rect(0, 0, 1080, 600, "F");

   doc.addImage(canvasImg, "PNG", 70, 50 ,160, 90);
   doc.text(125, 200, "proficiensy.com");
   doc.save("all-test.pdf");
}

/*******************************************************
 * /               download chart as a pdf          * /
 ********************************************************/

document.getElementById("allChartbtn").addEventListener("click", allChartPDF);
//donwload pdf from original canvas
function allChartPDF() {
   var canvas4 = document.querySelector("#allTestChart");
   var canvas3 = document.querySelector("#trendTestChart");
   var canvas2 = document.querySelector("#scoreChart");
   var canvas = document.querySelector("#testChart");

   var canvas4Img = canvas4.toDataURL("image/png", 1.0);
   var canvas3Img = canvas3.toDataURL("image/png", 1.0);
   var canvas2Img = canvas2.toDataURL("image/png", 1.0);
   var canvasImg = canvas.toDataURL("image/png", 1.0);

   var doc = new jsPDF();
   doc.setFontSize(18);

   doc.addImage(canvas4Img, "PNG", 30, 200);
   doc.addImage(canvas3Img, "PNG", 30, 100);
   doc.addImage(canvas2Img, "PNG", 100, 10, 100, 60);
   doc.addImage(canvasImg, "PNG", 10, 10, 80, 80);
   doc.text(80, 280, "proficiensy.com");

   doc.save("all-report-test.pdf");
}
