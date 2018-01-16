$(document).ready(function () {
    var initialX = 0;
    var initialY = 0;
    
    var points = [[initialX, initialY]];
    
    $('span.grid-cell').on('click',function(){
        const x = $(this).data('x');
        const y = $(this).data('y');
        
        points.push([x, y]);
        if (x == initialX && y == initialY) {
            points.splice(0,1);
            points.splice(points.length-1, 1);
            console.log(points);
            colorShape(points);
        }

        // On sauvegarde les valeurs du premier point
        if (initialX == 0 && initialY ==0) {
            initialX = x;
            initialY = y;
        }
        
        $(this).css('backgroundColor', 'blue');
        /*$('span.grid-cell').hover(function() {
           $(this).css('backgroundColor', 'green'); 
        }, function() {
            
        });*/
    });
    
    function colorShape(points) {
        const firstPoint = points[0];
        const secondPoint = points[1];
        const thirdPoint = points[2];

        const minX = getMinX(firstPoint, secondPoint, thirdPoint);
        const maxX = getMaxX(firstPoint, secondPoint, thirdPoint);
        const minY = getMinY(firstPoint, secondPoint, thirdPoint);
        const maxY = getMaxY(firstPoint, secondPoint, thirdPoint);

        console.log(minX);
        console.log(minY);
        console.log(maxX);
        console.log(maxY);

        for (i = minX; i <= maxX; i++) {
            for (j = minY; j <= maxY; j++) {
                colorPoint(i, j);
            }
        }

        points.splice(0,1);

        if (points.length >= 3) {
            colorShape(points);
        }
    }

    function getMinX(firstPoint, secondPoint, thirdPoint) {
        var minX = 10;
        if (firstPoint[0] > 0 && firstPoint[0] < minX) {
            minX = firstPoint[0];
        }
        if (secondPoint[0] > 0 && secondPoint[0] < minX) {
            minX = secondPoint[0];
        }
        if (thirdPoint[0] > 0 && thirdPoint[0] < minX) {
            minX = thirdPoint[0];
        }

        return minX;
    }

    function getMaxX(firstPoint, secondPoint, thirdPoint) {
        var maxX = 0;
        if (firstPoint[0] > maxX && firstPoint[0] <= 10) {
            maxX = firstPoint[0];
        }
        if (secondPoint[0] > maxX && secondPoint[0] <= 10) {
            maxX = secondPoint[0];
        }
        if (thirdPoint[0] > maxX && thirdPoint[0] <= 10) {
            maxX = thirdPoint[0];
        }

        return maxX;
    }

    function getMinY(firstPoint, secondPoint, thirdPoint) {
        var minY = 10;
        if (firstPoint[1] > 0 && firstPoint[1] < minY) {
            minY = firstPoint[1];
        }
        if (secondPoint[1] > 0 && secondPoint[1] < minY) {
            minY = secondPoint[1];
        }
        if (thirdPoint[1] > 0 && thirdPoint[1] < minY) {
            minY = thirdPoint[1];
        }

        return minY;
    }

    function getMaxY(firstPoint, secondPoint, thirdPoint) {
        var maxY = 0;
        if (firstPoint[1] > maxY && firstPoint[1] <= 10) {
            maxY = firstPoint[1];
        }
        if (secondPoint[1] > maxY && secondPoint[1] <= 10) {
            maxY = secondPoint[1];
        }
        if (thirdPoint[1] > maxY && thirdPoint[1] <= 10) {
            maxY = thirdPoint[1];
        }

        return maxY;
    }

    function colorPoint(x, y) {
        $('span.grid-cell').each(function() {
           if ($(this).data('x') == x && $(this).data('y') == y) {
                $(this).css('backgroundColor', 'yellow');
           }
        });
    }
});
