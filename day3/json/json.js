let text = '{ "myself" : [' +
'{"firstName":"Ivan" , "lastName":"Santos"},' +
'{"age":"22" , "email":"santosrafaelivan0630@gmail.com"},' + 
'{"address":"Pasig City"} ]}';



const obj = JSON.parse(text);

document.getElementById("demo").innerHTML = 
"Name: " + obj.myself[0].firstName + " " + obj.myself[0].lastName + "<br>" +
"Age: " + obj.myself[1].age + "<br>" + 
"Email: " + obj.myself[1].email + "<br>" +
"Address: " + obj.myself[2].address;

