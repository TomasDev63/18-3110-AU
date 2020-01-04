# Secure Web Application Development Coursework (M2M)
## 18-3110-AU

### Introduction
M2M allows users to view the messages over the [Simple Object Access Protocol](https://en.wikipedia.org/wiki/SOAP) via the [EE M2M Connect Service](https://m2mconnect.ee.co.uk/). It also lets users control switch, fan, keypad and temperature states.

### Access
Use your M2M CONNECT Username and Password to view the messages.

### Controls
{switch_1:true; switch_2:false; switch_3:false; switch_4:true; fan:true; heater:20; keypad:5; id:18-3110-AU}

* **All** or only **some** controls can be used  
```{switch_3:false; switch_4:true; id:18-3110-AU}```

* Order of the controls doesn't matter  
```{switch_3:false; switch_1:true; id:18-3110-AU}```

* Only messages with id:18-3110-AU should show up  
```{id:18-3110-AU}```

* It should be included at the end of the message  
```{keypad:5; id:18-3110-AU}```

* Regular message can be included before using the controls  
```Hello {switch_2:false; id:18-3110-AU}```
