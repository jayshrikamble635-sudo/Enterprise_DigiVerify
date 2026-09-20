<style>
body{
    margin:0;
    font-family:Arial;
    display:flex;
}

/* Sidebar */
.sidebar{
    width:220px;
    height:100vh;
    background:#0d6efd;
    color:white;
    position:fixed;
    padding-top:20px;
}

/* Links */
.sidebar h2{
    text-align:center;
    margin-bottom:30px;
}

.sidebar a{
    display:block;
    color:white;
    padding:15px;
    text-decoration:none;
}

.sidebar a:hover{
    background:#084298;
}

/* Main content */
.main{
    margin-left:220px;
    padding:20px;
    width:100%;
}

/* 📱 Mobile Responsive */
@media (max-width:768px){
    .sidebar{
        width:100%;
        height:auto;
        position:relative;
    }

    .main{
        margin-left:0;
    }

    .sidebar a{
        text-align:center;
        border-top:1px solid rgba(255,255,255,0.2);
    }
}
</style>