<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triangles</title>
    <style>
        html, body {
        width:  100%;
        height: 100%;
        margin: 0;
        background: rgb(30,30,30);
        }

        #C{
            /*  */
        }
    </style>
</head>
<body>
<canvas id="C"></canvas>
<script>
window.onload = function () {
  let ctx = document.getElementById("C"),
    c = ctx.getContext("2d"),
    w,
    h;
  fitCanvas();
  
  let mouse = {x: w/2, y: h/2},
      last_mouse = {};
  
  function dist(A,B){
    return Math.sqrt(Math.pow(A.x-B.x,2)+Math.pow(A.y-B.y,2));
  }
  class point{
    constructor(x,y,s){
      this.x = x;
      this.y = y;
      this.shown = false;
      this.s = s;
    }
    update(x,y,s){
      this.x = x;
      this.y = y;
      this.s = s;
    }
    show(color,line_thickness){
      if(!this.shown){
        //c.fillStyle=color;
        //c.fillRect(this.x-size/2,this.y-size/2,size,size);
        
        c.fillStyle=color;
        c.beginPath();
        c.arc(this.x,this.y,3/2,0,2*Math.PI);
        c.lineWidth=line_thickness;
        c.fill();
        
        this.shown = true;
      }
    }
    show2(color,line_thickness){
      if(!this.shown){
        
        c.strokeStyle=color;
        c.beginPath();
        c.arc(this.x,this.y,this.s/2,0,2*Math.PI);
        c.lineWidth=line_thickness;
        c.stroke();
        
        this.shown = true;
      }
    }
    cleanup(){
      this.shown = false;
    }
  }
  class edge{
    constructor(A,B){
      this.a = A;
      this.b = B;
      this.l = dist(A,B);
      this.shown = false;
    }
    update(A,B){
      this.a = A;
      this.b = B;
      this.l = dist(A,B);
    }
    show(color,line_thickness){
      if(!this.shown){
        c.strokeStyle=color;
        c.beginPath();
        c.lineTo(this.a.x,this.a.y);
        c.lineTo(this.b.x,this.b.y);
        c.lineWidth=line_thickness;
        c.stroke();
        
        this.shown = true;
      }
    }
    cleanup(){
      this.shown = false;
    }
  }
  class triangle{
    constructor(A,B,C){
      //define points
      this.a = A;
      this.b = B;
      this.c = C;
      //define main edges
      this.ab = new edge(this.a,this.b);
      this.bc = new edge(this.b,this.c);
      this.ca = new edge(this.c,this.a);
      //calculate and define curcumicenter
      this.scc = this.curcumicenter();
      this.sr = this.curcumiradius();
      this.s = new point(this.scc.x,this.scc.y,this.sr);
      //define edges of the triangle
      this.as = new edge(this.a,this.s);
      this.bs = new edge(this.b,this.s);
      this.cs = new edge(this.c,this.s);
    }
    move(m){
    //update point c
    this.c = m;
    //update edges
    this.ab.update(this.a,this.b);
    this.bc.update(this.b,this.c);
    this.ca.update(this.c,this.a);
    //update curcumicenter  
    this.scc = this.curcumicenter();
    this.sr = this.curcumiradius();
    this.s.update(this.scc.x,this.scc.y,this.sr);
    //s
    this.as.update(this.a,this.s);
    this.bs.update(this.b,this.s);
    this.cs.update(this.c,this.s);
  }
    curcumicenter(){
      let D = 2*(
                this.a.x*(this.b.y-this.c.y)+
                this.b.x*(this.c.y-this.a.y)+
                this.c.x*(this.a.y-this.b.y)
              );
      let S = {
        x: (
            (this.a.x*this.a.x+this.a.y*this.a.y)*(this.b.y-this.c.y)+
            (this.b.x*this.b.x+this.b.y*this.b.y)*(this.c.y-this.a.y)+
            (this.c.x*this.c.x+this.c.y*this.c.y)*(this.a.y-this.b.y)
           )/D,
        y: (
            (this.a.x*this.a.x+this.a.y*this.a.y)*(this.c.x-this.b.x)+
            (this.b.x*this.b.x+this.b.y*this.b.y)*(this.a.x-this.c.x)+
            (this.c.x*this.c.x+this.c.y*this.c.y)*(this.b.x-this.a.x)
           )/D
      }
      return S;
    }
    curcumiradius(){
      this.A = this.bc.l;
      this.B = this.ca.l;
      this.C = this.ab.l;
      
      return (2*this.A*this.B*this.C)/
             Math.sqrt((this.A+this.B+this.C)*(-this.A+this.B+this.C)*(this.A-this.B+this.C)*(this.A+this.B-this.C));
    }
    show_main_edges(){
      this.ab.show("white",0.1);
      this.bc.show("white",0.1);
      this.ca.show("white",0.1);
    }
    show_other_edges(){
      this.as.show("#00FFFF",0.1);
      this.bs.show("#00FFFF",0.1);
      this.cs.show("#00FFFF",0.1);
    }
    show_main_points(){
      this.a.show("white",1);
      this.b.show("white",1);
      this.c.show("white",1);
    }
    show_other_points(){
        this.s.show2("#00FFFF",0.1);
        this.s.show("#00FFFF",0.1);
    }
    show(em,eo,pm,po){
      if(em){
          this.show_main_edges();
      }
      if(eo){
          this.show_other_edges();
      }
      if(pm){
          this.show_main_points();
      }
      if(po){
          this.show_other_points();
      }
    }
    iterate(it){
      this.ta = new triangle(this.b,this.c,this.s);
      this.tb = new triangle(this.a,this.c,this.s);
      this.tc = new triangle(this.a,this.b,this.s);
      if(it < 3){
        this.ta.iterate(it+1);
        this.tb.iterate(it+1);
        this.tc.iterate(it+1);
      }
      this.ta.show(0,0,1,1);
      this.tb.show(0,0,1,1);
      this.tc.show(0,0,1,1);
    }
    cleanup(){
      this.a.cleanup();
      this.b.cleanup();
      this.c.cleanup();
      this.s.cleanup();
      
      this.ab.cleanup();
      this.bc.cleanup();
      this.ca.cleanup();
      
      this.as.cleanup();
      this.bs.cleanup();
      this.cs.cleanup()
    }
  }
  
  let pts = [],
      mouse_pt = new point(mouse.x,mouse.y),
      tris = [],
      num = 20,
      r = h/3;
  
  for(let i = 0, len = num; i < len; i++){
    pts.push(new point(w/2+r*Math.cos(i*2*Math.PI/len),h/2+r*Math.sin(i*2*Math.PI/len),3));
  }
  
  let A, B;
  
  for(let i = 0, len = num; i < len; i++){
    A = i;
    B = i+1;
    if(B >= len){
      B = 0;
    }
    tris.push(new triangle(pts[A],pts[B],mouse_pt));
  }
  
  
  function draw() {
    mouse_pt.update(mouse.x,mouse.y);
    mouse_pt.show();
    
    for(let i = 0, len = tris.length; i < len; i++){
      tris[i].move(mouse_pt);
      tris[i].iterate(0);
      tris[i].show(1,1,1,1);
      tris[i].cleanup();
    }
  }
  
  ctx.addEventListener(
    "mousemove",
    function(e) {
      last_mouse.x = mouse.x;
      last_mouse.y = mouse.y;

      mouse.x = e.pageX - this.offsetLeft;
      mouse.y = e.pageY - this.offsetTop;
    },
    false
  );
  
  function fitCanvas() {
    w = ctx.width = window.innerWidth;
    h = ctx.height = window.innerHeight;
  }
  function loop() {
    fitCanvas();
    draw();
    window.requestAnimationFrame(loop);
  }
  window.requestAnimationFrame(loop);
};
</script>
</body>
</html>