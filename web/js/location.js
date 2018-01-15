$(document).ready(function () {
    let initialX = 0;
    let initialY = 0;
    
    let points = [[initialX, initialY]];
    
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
        
        if (initialX == 0 && initialY ==0) {
            initialX = x;
            initialY = y;
        }
        
        $(this).css('backgroundColor', 'blue');
        $('span.grid-cell').hover(function() {
           $(this).css('backgroundColor', 'green'); 
        }, function() {
            
        });
    });
    
    function colorShape(points) {
     //TODO
    }
});
