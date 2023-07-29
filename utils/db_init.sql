create table user
(
    id   int          not null primary key AUTO_INCREMENT ,
   
    username varchar(100) not null,
  
    password varchar(100) not null,
    
    
);
create table pet
(
    id   int          not null primary key AUTO_INCREMENT ,
   
    health int not null,
  
    happiness int not null,
    clean int not null,
    age int not null,
    userid int not null foreign key references user(id),
    
    
);

