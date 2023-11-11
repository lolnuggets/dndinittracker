function getCookie(name) {
  const cookieString = document.cookie;
  const cookies = cookieString.split('; ');

  for (const cookie of cookies) {
    const [cookieName, cookieValue] = cookie.split('=');
    if (cookieName === name) {
      return cookieValue;
    }
  }

  return null; // Cookie not found
}

const item = document.querySelectorAll('.initiative-item');
item.forEach(function (item) {
	var isplayer = false;
	if (item.id.substring(0,7) == "player-")
		isplayer = true;

	// determine id aswell as if clicked is player or not
	var player;
	if (isplayer)
		player = item.id.substring(7);
	else
		player = item.id.substring(4);

	var open = false;
	item.addEventListener('click', function () {
		if (open)
			open = false;
		else
			open = true;
		console.log(player + " : " + isplayer);
	});
});


/*
		const xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState === 4 && xhr.status === 200) {
				if (open) {
					let dropdown = document.createElement("div");
					dropdown.className = "dropdown";
					dropdown.id = "dropdown-"+player;

					const data = xhr.responseText.split("-");


					let innate = document.createElement("div");
					let inner = document.createElement("p");
					innate.className = "innate-statblock";
					innate.innerHTML = "<div class=\"stat-head\">INNATE_STATS:</div>";
					inner.innerHTML = "Max HP:("+data[1]+")<br>level:("+data[0]+")"
					inner.style = "margin-top:0px";
					innate.appendChild(inner);
					dropdown.appendChild(innate);

					let passive = document.createElement("div");
					inner = document.createElement("p");
					passive.className = "passive-statblock";
					passive.innerHTML = "<div class=\"stat-head\">PASSIVE_ABILITIES:</div>";
					inner.innerHTML = "Perception("+data[2]+"),&emsp;Investigation("+data[3]+"),<br>Insight("+data[4]+"),&emsp;Darkvision("+data[5]+"ft)";
					inner.style = "margin-top:0px";
					passive.appendChild(inner);
					dropdown.appendChild(passive);
					
					let condition = document.createElement("div");
					inner = document.createElement("p");
					condition.className = "condition-statblock";
					condition.innerHTML = "<div class=\"stat-head\">CONDITIONS:</div>";
					inner.style = "margin-top:0px;font-size:10px";

					if (data[6] != "none"){
						data[6].split("/").forEach(function(val, i) {
							if (i != 0) {
								inner.innerHTML += ",";
							}
							if (i != 0 && i % 3 == 0) {
								inner.innerHTML += "<br>";
							} else if (i != 0) {
								inner.innerHTML += " ";
							}
							inner.innerHTML+= val;
						});
					}

					if (data[7] != 0) {
						if (data[6] != "none") {
							inner.innerHTML+= ",";
							if (data[6].split("/").length % 3 == 0)
								inner.innerHTML+= "<br>";
							else
								inner.innerHTML+= " ";
						}
						inner.innerHTML+= "exhaustion("+ data[7] +")";
					}

					if (data[6] == "none" && data[7] == 0) {
						inner.innerHTML += "Not affected by any conditions.";
					}
					condition.appendChild(inner);
					dropdown.appendChild(condition);

					if (getCookie('selected') == player || (window.location.href.search("dmview") !== -1 && getCookie('loggedperms') == "dm")) {
						const msgBox1 = document.createElement('div');
						msgBox1.className = 'msg-box';
						msgBox1.style.display = 'inline-block';
						msgBox1.style.verticalAlign = 'top';
						msgBox1.style.margin = '5px';
						msgBox1.style.padding = '5px';
						msgBox1.style.paddingBottom = '11px';

						const h2_1 = document.createElement('h2');
						h2_1.style.marginTop = '5px';
						h2_1.textContent = 'Edit character...';

						const form1 = document.createElement('form');
						form1.action = '../scripts/battleupdatechar.php';
						form1.method = 'post';

						const input1 = document.createElement('input');
						input1.type = 'hidden';
						input1.name = 'id';
						input1.value = player;

						const label1 = document.createElement('label');
						label1.setAttribute('for', 'hp');
						label1.textContent = 'Cur. HP: ';
						const inputHP = document.createElement('input');
						inputHP.id = 'hp';
						inputHP.type = 'number';
						inputHP.name = 'hp';
						inputHP.style.width = '40px';
						inputHP.setAttribute('min', '0');
						inputHP.setAttribute('step', '1');

						const label2 = document.createElement('label');
						label2.setAttribute('for', 'ac');
						label2.textContent = ' Armor Class: ';
						const inputAC = document.createElement('input');
						inputAC.id = 'ac';
						inputAC.type = 'number';
						inputAC.name = 'ac';
						inputAC.style.width = '40px';
						inputAC.setAttribute('min', '0');
						inputAC.setAttribute('step', '1');

						const label3 = document.createElement('label');
						label3.setAttribute('for', 'init');
						label3.textContent = 'Initiative: ';
						const inputInit = document.createElement('input');
						inputInit.id = 'init';
						inputInit.type = 'number';
						inputInit.name = 'init';
						inputInit.style.width = '40px';
						inputInit.setAttribute('min', '0');
						inputInit.setAttribute('step', '1');

						const button1 = document.createElement('button');
						button1.type = 'submit';
						button1.name = 'edit';
						button1.textContent = 'Submit';

						form1.appendChild(input1);
						form1.appendChild(label1);
						form1.appendChild(inputHP);
						form1.appendChild(label2);
						form1.appendChild(inputAC);
						form1.appendChild(label3);
						form1.appendChild(inputInit);
						form1.appendChild(document.createTextNode('\u2003'))
						form1.appendChild(button1);

						msgBox1.appendChild(h2_1);
						msgBox1.appendChild(form1);

						const msgBox2 = document.createElement('div');
						msgBox2.className = 'msg-box';
						msgBox2.style.display = 'inline-block';
						msgBox2.style.verticalAlign = 'top';
						msgBox2.style.margin = '5px';
						msgBox2.style.padding = '5px';

						const h2_2 = document.createElement('h2');
						h2_2.style.marginTop = '5px';
						h2_2.style.marginBottom = '5px';
						h2_2.textContent = 'Actions.';

						const form2 = document.createElement('form');
						form2.action = '../scripts/battleupdatechar.php';
						form2.method = 'post';

						const input2 = document.createElement('input');
						input2.type = 'hidden';
						input2.name = 'id';
						input2.value = player;

						const div1 = document.createElement('div');
						div1.style.display = 'inline-block';

						const inputDmg = document.createElement('input');
						inputDmg.id = 'dmg';
						inputDmg.type = 'number';
						inputDmg.name = 'dmg';
						inputDmg.style.width = '40px';
						inputDmg.setAttribute('min', '0');
						inputDmg.setAttribute('step', '1');

						const button2 = document.createElement('button');
						button2.type = 'submit';
						button2.name = 'itr';
						button2.textContent = '+1 Priority';

						const button3 = document.createElement('button');
						button3.type = 'submit';
						button3.name = 'deit';
						button3.textContent = '-1 Priority';

						div1.appendChild(inputDmg);
						div1.appendChild(document.createElement("br"));
						div1.appendChild(button2);
						div1.appendChild(document.createElement("br"));
						div1.appendChild(button3);

						const div2 = document.createElement('div');
						div2.style.display = 'inline-block';

						const button4 = document.createElement('button');
						button4.type = 'submit';
						button4.name = 'deal';
						button4.textContent = 'Deal';

						const button5 = document.createElement('button');
						button5.type = 'submit';
						button5.name = 'heal';
						button5.textContent = 'Heal';

						const button6 = document.createElement('button');
						button6.type = 'submit';
						button6.name = 'con';
						button6.textContent = 'Conditions';

						const button7 = document.createElement('button');
						button7.type = 'submit';
						button7.name = 'next';
						button7.textContent = 'Next turn';

						div2.appendChild(button4);
						div2.appendChild(button5);
						div2.appendChild(document.createElement("br"));
						div2.appendChild(button6);
						div2.appendChild(document.createElement("br"));
						div2.appendChild(button7);

						form2.appendChild(input2);
						form2.appendChild(div1);
						form2.appendChild(div2);

						msgBox2.appendChild(h2_2);
						msgBox2.appendChild(form2);

						dropdown.appendChild(msgBox1);
						dropdown.appendChild(msgBox2);
					}

					item.after(dropdown);
				} else {
					let dropdown = document.getElementById("dropdown-"+item.id.substring(7));
					dropdown.remove();
				}
			}
		};
		xhr.open("POST", "../scripts/dropdown.php", true); // Replace with your server endpoint
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send("id="+item.id);
	})});*/