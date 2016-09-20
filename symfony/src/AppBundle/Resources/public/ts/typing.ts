/// <reference path="../../../../../typings/bundle.d.ts" />

(function() {
  'use strict';

  var words = [
    'apple',
    'imagine',
    'supply',
    'fun',
    'happy',
    'air',
    'sky',
    'access',
    'afford',
    'amount',
    'approximate',
    'arrange',
    'bill',
    'book',
    'budget',
    'charge',
    'check',
    'colleague',
    'committee',
    'condition',
    'confirm',
    'consider',
    'construction',
    'contain',
    'contract',
    'convenient',
    'corporate',
    'current',
    'deadline',
    'deal',
    'demand',
    'despite',
    'divide',
    'downstairs',
    'effect',
    'efficient',
    'equipment',
    'event',
    'except',
    'executive',
    'exhibition',
    'expand',
    'expense',
    'familiar',
    'figure',
    'firm',
    'fold',
    'form',
    'fundamental',
    'gain',
    'gathering',
    'handle',
    'hardly',
    'headquarters',
    'hold',
    'immediate',
    'indicate',
    'inspect',
    'install',
    'interest',
    'interview',
    'invest',
    'meantime',
    'neighbor',
    'notification',
    'passenger',
    'payment',
    'personnel',
    'position',
    'prefer',
    'previous',
    'profit',
    'promote',
    'proposal',
    'punctual',
    'purchase',
    'qualification',
    'receipt',
    'recently',
    'reduce',
    'refer',
    'refund',
    'register',
    'remain',
    'repair',
    'replace',
    'require',
    'reservation',
    'result',
    'resume',
    'revenue',
    'serve',
    'shelf',
    'ship',
    'sign',
    'therefore',
    'unfortunately',
    'update',
    'upgrade',
    'vacant',
    'valuable',
    'variety',
    'workshop',
    'worth',
  ];

  var currentWord;
  var currentLocation;
  var score;
  var miss;
  var timer;
  var target = document.getElementById('target');
  var scoreLabel = document.getElementById('score');
  var missLabel = document.getElementById('miss');
  var timerLabel = document.getElementById('timer');
  var isStarted;
  var timerId;
  var errorSound = document.getElementById('error');

  function init() {
    currentWord = 'enter to start';
    currentLocation = 0;
    score = 0;
    miss = 0;
    timer = 60;
    target.innerHTML = currentWord;
    scoreLabel.innerHTML = score;
    missLabel.innerHTML = miss;
    timerLabel.innerHTML = timer;
    isStarted = false;
  }

  init();

  function updateTimer() {
    timerId = setTimeout(function() {
      timer--;
      timerLabel.innerHTML = timer;
      if (timer <= 0) {
        var accuracy = (score + miss) === 0 ? '0.00' : ((score / (score + miss)) * 100).toFixed(2);
        alert(score + ' letters, ' + miss + ' miss! ' + accuracy + ' % accuracy');

        clearTimeout(timerId);
        init();
        return;
      }
      updateTimer();
    }, 1000);
  };

  function setTarget() {
    currentWord = words[Math.floor(Math.random() * words.length)];
    target.innerHTML = currentWord;
  };

  // window.addEventListener('click', function() {
  //   if (!isStarted) {
  //     isStarted = true;
  //     setTarget();
  //     updateTimer();
  //   }
  // });

  window.addEventListener('keyup', function(e) {
    if (!isStarted) {
      if (e.keyCode === 13) {
        isStarted = true;
        setTarget();
        updateTimer();
      }
      return;
    }
    if (String.fromCharCode(e.keyCode) === currentWord[currentLocation].toUpperCase()) {
      currentLocation++;
      var placeholder = '';
      for (var i = 0; i < currentLocation; i++) {
        placeholder += '_';
      }
      target.innerHTML = placeholder + currentWord.substring(currentLocation);
      score++;
      scoreLabel.innerHTML = score;

      if (currentLocation === currentWord.length) {
        currentLocation = 0;
        setTarget();
      }
    } else {
      miss++;
      missLabel.innerHTML = miss;
      // errorSound.play();
    }
  });
})();

