let words = [];
let articles = [];
let oldArticles = [];
let word = 'First';
let wordCounter = 0;
var wordTimer = 18;
let lastTime = 0;
let canvas;
let api;

let count = 1, countScale = 1, topLimit = 800, bottomLimit = 0;

var myTextsize = 61;
var myTextColor = '#4f65ea';
var myBackgroundColor = '#e9c0e5';

// GUI
let visible = false;
let gui;

// dynamic parameters

function setup() {
    colorMode(RGB);
    canvas = createCanvas(window.innerWidth, window.innerHeight);

    sliderRange(0, 200, 1);
    gui = createGui('p5.gui');
    gui.addGlobals('myTextColor', 'myBackgroundColor', 'myTextsize', 'wordTimer');
    gui.hide()

    lastTime = new Date();
    getArticleList();
}


function draw() {
  background( backgroundColor() );
  fill( typeColor() );

  textSize(myTextsize);
  noStroke();
  textAlign(CENTER, CENTER);
  nextWord();
  text(word, (width/2), (height/2));
}

function nextWord(){
  var now = new Date().getTime();
  var diff = now - lastTime.getTime();
  if( diff >= wordTimer){
    lastTime = new Date();
    word = words[wordCounter];
    wordCounter++;
    if( wordCounter > words.length ){
      wordCounter = 0;
      console.log("On To The Next One!")
      getNextArticle();
    }
  }
}


function pingpong(){
  if (count >= topLimit || count <= bottomLimit){
    countScale = countScale * -1;
  }
  count = count + countScale;
  return count;
}

function backgroundColor(){
  c = lerpColor( color(myBackgroundColor), color(myTextColor), pingpong()/topLimit );
  return c;
}

function typeColor(){
  c = lerpColor( color(myTextColor), color(myBackgroundColor), pingpong()/topLimit );
  return c;
}

function getArticleList(){
    let toFetch = "getcnnlist.php";

    fetch(toFetch)
        .then(function(response) {
            return response.json();
        }).then(function(myJson) {
            let returned_html = new DOMParser().parseFromString(atob(myJson.data.html), 'text/html');
            let allLI = returned_html.getElementsByClassName("card--lite");
            articles =  Array.from(allLI).map( (el) => el.querySelector('a').getAttribute('href'));

            getNextArticle()
        });
}



function getNextArticle(){
  let nextURL =  articles.shift();
  oldArticles.push(nextURL);
  getNewArticle("https://lite.cnn.com" + nextURL);
}

function getNewArticle(url){
    let toFetch = 'getcnn.php?&url='+url;
    fetch(toFetch)
        .then(function(response) {
            // console.log(response);
            return response.json();
        }).then(function(myJson) {
            let returned_html = new DOMParser().parseFromString(atob(myJson.data.html), 'text/html');
            let story = returned_html.getElementsByTagName('article')[0].innerText;
            setWords(story);
        });
}


function setWords(newWords){
  let nw = newWords.replace(/[^a-z0-9\s]/gi, '').replace(/(\r\n|\n|\r)/gm, "");
//   nw.replace("Source CNN", "");
  let word_array = nw.split(" ");
  word_array = word_array.filter(function(str) {
      return /\S/.test(str);
  });
  words = word_array;
}


function keyTyped() {
  if ((key == 'g') || (key == 'G')) {
    visible = !visible;
    if(visible) gui.show(); else gui.hide();
  }
}

window.onresize = function() {
  var w = window.innerWidth;
  var h = window.innerHeight;
  canvas.size(w,h);
  width = w;
  height = h;
};
