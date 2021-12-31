/*(function() {*/

/* Define the number of leaves to be used in the animation */
var NUMBER_OF_LEAVES = 30;

/*
 Called when the "Falling Leaves" page is completely loaded.
 */
function hbInit() {
    /* Get a reference to the element that will contain the leaves */
    var container = document.getElementById('hongbao_animation');
    container.innerHTML =''; // 清空
    /* Fill the empty container with new leaves */
    try {
        for (var i = 0; i < NUMBER_OF_LEAVES; i++) {
            container.appendChild(createALeaf());
        }
    }
    catch(e) {
    }
}

/*
 Receives the lowest and highest values of a range and
 returns a random integer that falls within that range.
 */
function randomInteger(low, high) {
    return low + Math.floor(Math.random() * (high - low));
}

/*
 Receives the lowest and highest values of a range and
 returns a random float that falls within that range.
 */
function randomFloat(low, high) {
    return low + Math.random() * (high - low);
}

/*
 Receives a number and returns its CSS pixel value.
 */
function pixelValue(value) {
    return value + 'px';
}

/*
 Returns a duration value for the falling animation.
 */
function durationValue(value) {
    return value + 's';
}

/*
 Uses an img element to create each leaf. "Leaves.css" implements two spin
 animations for the leaves: clockwiseSpin and counterclockwiseSpinAndFlip. This
 function determines which of these spin animations should be applied to each leaf.

 */
function createALeaf() {
    /* Start by creating a wrapper div, and an empty img element */
    var leafDiv = document.createElement('div');
    var image = document.createElement('img');

    /* Randomly choose a leaf image and assign it to the newly created element */
    var imgnum = randomInteger(1, 11); // 图片名称
    image.src ='/images/hongbao/new/petal'+ imgnum + '.png';
    if( imgnum < 7){
        image.setAttribute('data-type','hb');
    }
    /* Position the leaf at a random location along the screen */
    leafDiv.className = 'hongbao_li';
    leafDiv.style.top = pixelValue(randomInteger(-250, -150));
    leafDiv.style.left = pixelValue(randomInteger(0, 1080));

    /* Randomly choose a spin animation */
    var spinAnimationName = (Math.random() < 0.5) ? 'clockwiseSpin':'counterclockwiseSpinAndFlip';        /* Set the -webkit-animation-name property with these values */
    leafDiv.style.webkitAnimationName ='fade, drop';
    leafDiv.style.animationName ='fade, drop';
    image.style.webkitAnimationName = spinAnimationName;
    image.style.animationName = spinAnimationName;
    //image.style.animationDirection = 'infinite';

    /* 随机下落时间 */
    var fadeAndDropDuration = durationValue(randomFloat(5, 15));

    /* 随机旋转时间 */
    var spinDuration = durationValue(randomFloat(2, 5));

    leafDiv.style.webkitAnimationDuration = fadeAndDropDuration + ', ' + fadeAndDropDuration;
    leafDiv.style.animationDuration = fadeAndDropDuration + ', ' + fadeAndDropDuration;

    // 随机delay时间
    var leafDelay = durationValue(randomFloat(0, 3));

    leafDiv.style.webkitAnimationDelay = leafDelay + ', ' + leafDelay;
    leafDiv.style.animationDelay = leafDelay + ', ' + leafDelay;
    image.style.webkitAnimationDuration = spinDuration;
    image.style.animationDuration = spinDuration;
    leafDiv.appendChild(image);
    return leafDiv;
}

/*   }
)();*/
