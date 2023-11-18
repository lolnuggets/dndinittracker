// info we need to get.
// list of players, (per player: name, hp and maxhp, ac, hp, initative, relationship, hidden, isturn

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

function sleep(ms) {
	return new Promise(resolve => setTimeout(resolve, ms));
}

function getColorAtPercent(percent) {
	// guard for edge cases
	if (percent > 1)
		percent = 1;
	if (percent < 0)
		percent = 0;

	// start of gradient
	var start = {
		red : 59,
		green : 156,
		blue : 59
	}

	// end for gradient
	var stop = {
		red : 143,
		green : 63,
		blue : 63
	}

	var rgb = {
		red : Math.floor((percent * start.red) + ( (1-percent) * stop.red)),
		green : Math.floor((percent * start.green) + ( (1-percent) * stop.green)),
		blue : Math.floor((percent * start.blue) + ( (1-percent) * stop.blue))
	}

	console.log("Percent : " + percent + ", rgb: " + rgb.red + ", " + rgb.green + ", " + rgb.blue);

	return rgbToHex(rgb.red, rgb.green, rgb.blue);
}

function rgbToHex(r, g, b) {
  // Ensure that the values are in the valid range (0 to 255)
  r = Math.min(255, Math.max(0, r));
  g = Math.min(255, Math.max(0, g));
  b = Math.min(255, Math.max(0, b));

  // Convert the values to hexadecimal and concatenate them
  const hexR = r.toString(16).padStart(2, '0');
  const hexG = g.toString(16).padStart(2, '0');
  const hexB = b.toString(16).padStart(2, '0');

  // Combine the hex values
  return `#${hexR}${hexG}${hexB}`;
}


var response;

function drawList() {

	const xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4 && xhr.status === 200) {

			if (xhr.responseText == response)
				return;

			response = xhr.responseText;

			// clear current list
			var initiativeListSection = document.getElementById("initiative-list");
			while (initiativeListSection.firstChild) {
					initiativeListSection.removeChild(initiativeListSection.firstChild);
			}

			var rkey = xhr.responseText.split("/");

			if (rkey[1] == "EMPTY") {
				let msg = document.createElement("div");
				msg.className = "msg-box";
				msg.innerHTML = "Currently no battle";
				document.getElementById("initiative-list").appendChild(msg);
				return;
			}

			let battlestate = document.createElement("div");
			battlestate.className = "msg-box";
			battlestate.style = "width:100%";
			battlestate.innerHTML = "battle is currently " + rkey[0];

			document.getElementById("initiative-list").appendChild(battlestate);

			var first = true
			const itemlist = rkey[1].split("|");
			itemlist.forEach(function(val) {

				const data = val.split("\\");
				// calculate status
				var condition = "";
				var color = "";
				var percenthp = parseInt(data[1]) / parseInt(data[4]);
				if (percenthp >= 1)
					condition = "healthy";
				else if (percenthp >= 0.85)
					condition = "ok";
				else if (percenthp >= 0.75)
					condition = "light injuries";
				else if (percenthp >= 0.5)
					condition = "moderatly injured";
				else if (percenthp >= 0.25)
					condition = "severe injuries";
				else if (percenthp >= 0.1)
					condition = "critical damage";
				else if (percenthp > 0.0)
					condition = "fatal damage";
				else if (percenthp <= 0.0)
					condition = "dead";

				color+=getColorAtPercent(percenthp);

				// drawing function
				let curitem = document.createElement("div");
				curitem.className = "initiative-item";
				if (data[3] == "true") {
					// currently this items turn
					curitem.style = "background-color:#1b3834"
				}

				var editstyle = "";
				if (first) {
					editstyle = "top:-10px;";
					// first elemtn add headers
					let name = document.createElement("div");
					name.className = "name hint";
					name.innerHTML = "name";
					curitem.appendChild(name);

					let status = document.createElement("div");
					status.className = "status hint";
					status.innerHTML = "status";
					curitem.appendChild(status);

					let hp = document.createElement("div");
					hp.className = "hp hint";
					hp.innerHTML = "hp";
					curitem.appendChild(hp);
					
					let ac = document.createElement("div");
					ac.className = "ac hint";
					ac.innerHTML = "ac";
					curitem.appendChild(ac);
					
					let init = document.createElement("div");
					init.className = "initiative hint";
					init.innerHTML = "init";
					curitem.appendChild(init);
					
					let relationship = document.createElement("div");
					relationship.className = "party hint";
					relationship.innerHTML = "relationship";
					curitem.appendChild(relationship);
					first = false;
				}

				// add breaks here
				for (let i = 0; i < 5; i++) {
					let lnbreak = document.createElement("div");
					lnbreak.className = "break";
					switch(i){
					case 0:
						lnbreak.style = "left: 508px;";
						break;
					case 1:
						lnbreak.style="left: 730px;";
						break;
					case 2:
						lnbreak.style="left: 835px;";
						break;
					case 3:
						lnbreak.style="left: 940px;";
						break;
					case 4:
						lnbreak.style="left: 1045px;";
						break;
					}
					curitem.appendChild(lnbreak);
				}

				if (data[6] == "PLAYER") {
					// drawing a player
					let id = data[0].replaceAll(" ", "-");
					curitem.id = "player-"+id;

					let name = document.createElement("div");
					name.className = "name";
					name.innerHTML = data[0];
					name.style= editstyle;
					curitem.appendChild(name);

					let status = document.createElement("div");
					status.className = "status";
					status.innerHTML = condition;
					status.style= "color: "+color+";" + editstyle;
					curitem.appendChild(status);

					let hp = document.createElement("div");
					hp.className = "hp";
					hp.innerHTML = data[1];
					if (playerperms == "player" && data[0] != player)
						hp.innerHTML = "?";

					hp.style= editstyle;
					curitem.appendChild(hp);
					
					let ac = document.createElement("div");
					ac.className = "ac";
					ac.innerHTML = data[5];
					ac.style= editstyle;
					curitem.appendChild(ac);
					
					let init = document.createElement("div");
					init.className = "initiative";
					init.innerHTML = data[2];
					init.style= editstyle;
					curitem.appendChild(init);
					
					let relationship = document.createElement("div");
					relationship.className = "party";
					relationship.innerHTML = "party";
					relationship.style= editstyle;
					curitem.appendChild(relationship);
				} else {
					// drawing an nps
					let id = data[0].replaceAll(" ", "-");
					curitem.id = "npc-"+id;

					let name = document.createElement("div");
					name.className = "name";
					name.innerHTML = data[0];
					name.style= editstyle;
					if (!(typeof data[7] == 'undefined' || data[7] == "false"))
						name.innerHTML += "(hidden)";
					curitem.appendChild(name);

					let status = document.createElement("div");
					status.className = "status";
					status.innerHTML = condition;
					status.style= "color: "+color+";" + editstyle;
					curitem.appendChild(status);

					let hp = document.createElement("div");
					hp.className = "hp";
					hp.innerHTML = data[1];
					if (playerperms == "player" || playerperms == "spectator")
						hp.innerHTML = "?";
					hp.style= editstyle;
					curitem.appendChild(hp);
					
					let ac = document.createElement("div");
					ac.className = "ac";
					if (data[5] == "none")
						data[5] = "";

					ac.innerHTML = data[5];
					if (playerperms == "player" || playerperms == "spectator")
						ac.innerHTML = "?";
					ac.style= editstyle;
					curitem.appendChild(ac);
					
					let init = document.createElement("div");
					init.className = "initiative";
					init.innerHTML = data[2];
					init.style= editstyle;
					curitem.appendChild(init);
					
					let relationship = document.createElement("div");
					relationship.className = "party";
					relationship.innerHTML = data[6];
					relationship.style= editstyle;
					curitem.appendChild(relationship);
				}

				// append here

				if ((typeof data[7] == 'undefined' || data[7] == "false") || playerperms == "dm"){
					document.getElementById("initiative-list").appendChild(curitem);
				}
			});

			addDropdown();
		}
	}
	xhr.open("GET", "../scripts/get-info.php", true); // ReplaceAll with your server endpoint
	xhr.send();
}

function drawDropdown(isplayer, id) {
	const xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4 && xhr.status === 200) {
			let dropdown = document.createElement("div");
			dropdown.className = "dropdown";
			if (isplayer)
				dropdown.id = "dropdown-player-"+id;
			else
				dropdown.id = "dropdown-npc-"+id;

			const data = xhr.responseText.split("-");

			if (isplayer || playerperms == "dm") {
				let innate = document.createElement("div");
				let inner = document.createElement("p");
				innate.className = "innate-statblock";
				innate.innerHTML = "<div class=\"stat-head\">INNATE_STATS:</div>";
				inner.innerHTML = "Max HP:("+data[1]+")<br>level:("+data[0]+")"
				inner.style = "margin-top:0px";
				innate.appendChild(inner);
				dropdown.appendChild(innate);
			}

			if (isplayer || playerperms == "dm") {
				let passive = document.createElement("div");
				inner = document.createElement("p");
				passive.className = "passive-statblock";
				passive.innerHTML = "<div class=\"stat-head\">PASSIVE_ABILITIES:</div>";
				inner.innerHTML = "Perception("+data[2]+"),&emsp;Investigation("+data[3]+"),<br>Insight("+data[4]+"),&emsp;Darkvision("+data[5]+" ft)";
				inner.style = "margin-top:0px";
				passive.appendChild(inner);
				dropdown.appendChild(passive);
			}
			
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

			// notes sections
			if (playerperms == "dm") {
				// add create button + use
			}

			if ((playerperms == "player" && id.replaceAll("-", " ") == player) || playerperms == "dm") {
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
				input1.value = id.replaceAll("-", " ");

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

				const input3 = document.createElement('input');
				input3.type = 'hidden';
				input3.name = 'npc';
				if (isplayer)
					input3.value = "false";
				else
					input3.value = "true";

				form1.appendChild(input3);
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
				input2.value = id.replaceAll("-", " ");

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

			if (playerperms == "dm" && !isplayer) {

				const msgBox3 = document.createElement('div');
				msgBox3.className = 'custom-button';
				msgBox3.style = "position:absolute;bottom:0px;left:0px;font-size:10px;width:50px;height:10px;padding:0px;";
				msgBox3.innerHTML = "NOTES";

				var opennotes = false;
				msgBox3.addEventListener('click', function() {
					if (opennotes) {
						undrawNotes(isplayer, id);
						opennotes = false;
					} else {
						drawNotes(isplayer, id);
						opennotes = true;
					}
				});

				dropdown.appendChild(msgBox3);

				// add statcard
				if (data[8] != "" && data[8] != "unset") {
					const msgBox4 = document.createElement('div');
					msgBox4.className = 'custom-button';
					msgBox4.style = "position:absolute;bottom:0px;left:50px;font-size:10px;width:50px;height:10px;padding:0px;";
					msgBox4.innerHTML = "STATS";

					msgBox4.addEventListener('click', function() {
						if (document.getElementById("api-statcard-" + id) != null) {
							document.getElementById("api-statcard-" + id).remove();
						} else {
							drawStats(id, data[8]);
						}
					});

					dropdown.appendChild(msgBox4);
				}
			}
			var item;
			if (isplayer)
				item = document.getElementById("player-"+id);
			else
				item = document.getElementById("npc-"+id);
			item.after(dropdown);

		}
	}
	let endpoint = "../scripts/dropdownplayer.php";
	if (!isplayer)
		endpoint = "../scripts/dropdownnpc.php";
	xhr.open("POST", endpoint, true); // ReplaceAll with your server endpoint
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send("id="+id.replaceAll("-", " "));
}

function fetchStats(api) {
	return new Promise((resolve, reject)=> {
		const xhr = new XMLHttpRequest();

		xhr.onreadystatechange = function() {
			if (xhr.readyState === 4) {
					if (xhr.status === 200) {
							resolve(xhr.responseText);
					} else {
							reject(new Error(`HTTP request failed with status ${xhr.status}`));
					}
			}
		}

		xhr.open('GET', 'https://www.dnd5eapi.co/api/monsters/'+api.replaceAll(" ","-"), true);
		xhr.send();
	});
}

async function drawStats(id, api) {
	try {
		const data = JSON.parse(await fetchStats(api));

		const statc = document.createElement("div");
		statc.className = "api-statcard";
		statc.id = "api-statcard-" + id;

		let leftblock = document.createElement("div")
		leftblock.style = "diplay:block;width:450px";
		// handles basics like hitpoints, ability checks etc

		// basic information
		let name = document.createElement("h2");
		name.innerHTML = data.name;
		name.style = "color:#822000;margin-top:0px;margin-bottom:0px";
		leftblock.appendChild(name);

		let align = document.createElement("p");
		align.innerHTML = data.size + " " + data.type + ", " + data.alignment;
		align.style = "margin:0px;margin-bottom:20px";
		leftblock.appendChild(align);

		// basic battle stats
		let ac = document.createElement("p");
		ac.innerHTML = "Armor class: " + data.armor_class[0].value;
		if (data.armor_class[0].type != "dex")
			ac.innerHTML += " ("+ data.armor_class[0].type +")"
		ac.style = "margin:0px;color:#822000";
		leftblock.appendChild(ac);

		let hp = document.createElement("p");
		hp.innerHTML = "Hit Points "+data.hit_points+" ("+data.hit_points_roll+")";
		hp.style = "margin:0px;color:#822000";
		leftblock.appendChild(hp);

		let speed = document.createElement("p");
		speed.innerHTML = "Speed none";

		//walking
		if (data.speed.walk != undefined) {
			if (speed.innerHTML != "Speed none")
				speed.innerHTML+=", ";
			else
				speed.innerHTML= "Speed ";
			speed.innerHTML+= " "+data.speed.walk;
		}

		//burrowing
		if (data.speed.burrow != undefined) {
			if (speed.innerHTML != "Speed none")
				speed.innerHTML+=", ";
			else
				speed.innerHTML= "Speed ";
			speed.innerHTML+= "burrow "+data.speed.burrow;
		}

		//climbing
		if (data.speed.climb != undefined) {
			if (speed.innerHTML != "Speed none")
				speed.innerHTML+=", ";
			else
				speed.innerHTML= "Speed ";
			speed.innerHTML+= "climb "+data.speed.climb;
		}

		//flying
		if (data.speed.fly != undefined) {
			if (speed.innerHTML != "Speed none")
				speed.innerHTML+=", ";
			else
				speed.innerHTML= "Speed ";
			speed.innerHTML+= "fly "+data.speed.fly;
		}

		//hovering
		if (data.speed.hover != undefined) {
			if (speed.innerHTML != "Speed none")
				speed.innerHTML+=", ";
			else
				speed.innerHTML= "Speed ";
			speed.innerHTML+= "hover "+data.speed.hover;
		} 

		//swimming
		if (data.speed.swim != undefined) {
			if (speed.innerHTML != "Speed none")
				speed.innerHTML+=", ";
			else
				speed.innerHTML= "Speed ";
			speed.innerHTML+= "swim "+data.speed.swim;
		}
		speed.style = "margin:0px;color:#822000";
		leftblock.appendChild(speed);

		// basic statistics
		let basicstats = document.createElement("div")
		const ivstats = [];

		let str = {
			name: "STR",
			value: data.strength,
			mod: Math.floor((data.strength - 10) / 2),
			save: Math.floor((data.strength - 10) / 2)
		}
		ivstats.push(str);

		let dex = {
			name: "DEX",
			value: data.dexterity,
			mod: Math.floor((data.dexterity - 10) / 2),
			save: Math.floor((data.dexterity - 10) / 2)
		}
		ivstats.push(dex);
		
		let con = {
			name: "CON",
			value: data.constitution,
			mod: Math.floor((data.constitution - 10) / 2),
			save: Math.floor((data.constitution - 10) / 2)
		}
		ivstats.push(con);

		let int = {
			name: "INT",
			value: data.intelligence,
			mod: Math.floor((data.intelligence - 10) / 2),
			save: Math.floor((data.intelligence - 10) / 2)
		}
		ivstats.push(int);

		let wis = {
			name: "WIS",
			value: data.wisdom,
			mod: Math.floor((data.wisdom - 10) / 2),
			save: Math.floor((data.wisdom - 10) / 2)
		}
		ivstats.push(wis);

		let cha = {
			name: "CHA",
			value: data.charisma,
			mod: Math.floor((data.charisma - 10) / 2),
			save: Math.floor((data.charisma - 10) / 2)
		}
		ivstats.push(cha);

		var proficiency = "";
		var skillprof = "";

		data.proficiencies.forEach((value)=>{
			if (value.proficiency.index.search("saving-throw") != -1) {
				switch(value.proficiency.index.substring(13)) {
				case "str":
					str.save=value.value;
					break;
				case "dex":
					dex.save=value.value;
					break;
				case "con":
					con.save=value.value;
					break;
				case "int":
					int.save=value.value;
					break;
				case "wis":
					wis.save=value.value;
					break;
				case "cha":
					cha.save=value.value;
					break;
				}
			} else if (value.proficiency.index.search("skill-") != -1) {
				if (skillprof != "") {
					skillprof +=", "
				} else {
					skillprof = "Skills: ";
				}
				skillprof += value.proficiency.name.substring(7) + " +" + value.value;
			} else {
				if (proficiency != "") {
					proficiency +=", "
				} else {
					proficiency = "Proficiency: ";
				}
				proficiency += value.proficiency.name;
			}
		});

		ivstats.forEach((stat)=>{
			let statblock = document.createElement("p");
			statblock.innerHTML = stat.name + "<br>"+stat.value+" ("+stat.mod+")";
			statblock.style = "color:#822000; display:inline-block;font-size:15px;margin-right:10px";
			basicstats.appendChild(statblock);

		});

		leftblock.appendChild(basicstats);

		let middleblock = document.createElement("div");
		// handles strange stats like saving throus, skills, immunites senses etc

		var savehtml = "";
		ivstats.forEach((stat)=>{
			if (stat.mod != stat.save) {
				if (savehtml != "") {
					savehtml +=", ";
				} else {
					savehtml = "Saving Throws";
				}

				savehtml+=" "+stat.name
				if (stat.save > 0)
					savehtml+=" +"+stat.save;
				else
					savehtml+=" "+stat.save;
			}
		});

		if (savehtml != "") {
			let throws = document.createElement("p");
			throws.innerHTML = savehtml;
			throws.style = "color:#822000;margin:10px;";
			middleblock.appendChild(throws);
		}

		if (skillprof != "none") {
			let skills = document.createElement("p");
			skills.innerHTML = skillprof;
			skills.style = "color:#822000;margin:10px;";
			middleblock.appendChild(skills);
		}

		if (proficiency != "none") {
			let skills = document.createElement("p");
			skills.innerHTML = proficiency;
			skills.style = "color:#822000;margin:10px;";
			middleblock.appendChild(skills);
		}

		let snss = "";
		for (let property in data.senses) {
		    if (data.senses.hasOwnProperty(property)) {
		        if (snss != "")
		        	snss += ", ";
		        else
		        	snss = "Senses: ";
		        snss+= property.replaceAll("_", " ")[0].toUpperCase()+property.replaceAll("_", " ").substring(1) + " " + data.senses[property];
		    }
		}

		let vlnb = "";
		data.damage_vulnerabilities.forEach((value) => {
			if (vlnb != "")
				vlnb+=", ";
			else
				vlnb = "Damage Vulnerabilities: ";
			vlnb+=value[0].toUpperCase()+value.substring(1);
		});

		let res = "";
		data.damage_resistances.forEach((value) => {
			if (res != "")
				res+=", ";
			else
				res = "Damage Resistances: ";
			res+=value[0].toUpperCase()+value.substring(1);
		});

		let imm= "";
		data.damage_immunities.forEach((value) => {
			if (imm != "")
				imm+=", ";
			else
				imm = "Damage Immunities: ";
			imm+=value[0].toUpperCase()+value.substring(1);
		});

		let cdimm= "";
		data.condition_immunities.forEach((value) => {
			if (cdimm != "")
				cdimm+=", ";
			else
				cdimm = "Condition Immunities: ";
			cdimm+=value.name;
		});

		if (snss != "") {
			let dmgres = document.createElement("p");
			dmgres.innerHTML = snss;
			dmgres.style = "color:#822000;margin:10px;";
			middleblock.appendChild(dmgres);
		}

		if (res != "") {
			let dmgres = document.createElement("p");
			dmgres.innerHTML = res;
			dmgres.style = "color:#822000;margin:10px;";
			middleblock.appendChild(dmgres);
		}

		if (imm != "") {
			let dmgimm = document.createElement("p");
			dmgimm.innerHTML = imm;
			dmgimm.style = "color:#822000;margin:10px;";
			middleblock.appendChild(dmgimm);
		}

		if (cdimm != "") {
			let conimm = document.createElement("p");
			conimm.innerHTML = cdimm;
			conimm.style = "color:#822000;margin:10px;";
			middleblock.appendChild(conimm);
		}

		let spoken = "none";
		if (data.languages != "")
			spoken = data.languages;
		let lngs = document.createElement("p");
		lngs.innerHTML = "Languages " + spoken;
		lngs.style = "color:#822000;margin:10px;";
		middleblock.appendChild(lngs);

		let chlng = document.createElement("p");
		chlng.innerHTML = "Challenge "+data.challenge_rating+" ("+data.xp+" XP)";
		chlng.style = "color:#822000;margin:10px;";
		middleblock.appendChild(chlng);

		let prof = document.createElement("p");
		prof.innerHTML = "Proficiency Bonus +" + data.proficiency_bonus;
		prof.style = "color:#822000;margin:10px;";
		middleblock.appendChild(prof);

		if (data.special_abilities.length > 0) {
			var rightblock = document.createElement("div");

			let special = document.createElement("div")
			special.style = "margin-bottom:20px";

			data.special_abilities.forEach((ability) => {
				let specialname = document.createElement("h3");
				specialname.style="margin:0px"
				specialname.innerHTML = ability.name;
				special.appendChild(specialname);

				let specialdesc = document.createElement("p");
				specialdesc.style="margin:0px;margin-bottom:10px"
				specialdesc.innerHTML = ability.desc;
				special.appendChild(specialdesc);
				rightblock.appendChild(special);	
			});
		}

		if (data.actions.length > 0) {
			//attacks
			var attacks = document.createElement("div")
			attacks.style = "width:1360px;display:block";

			let atitle = document.createElement("h2");
			atitle.innerHTML = "Actions";
			attacks.appendChild(atitle);

			data.actions.forEach((action)=> {
				let attack = document.createElement("div")

				let attackname = document.createElement("h3");
				attackname.style="margin:0px"
				attackname.innerHTML = action.name;
				attack.appendChild(attackname);

				let attackdesc = document.createElement("p");
				attackdesc.style="margin:0px; margin-bottom:10px";
				attackdesc.innerHTML = action.desc;
				attack.appendChild(attackdesc);
				attacks.appendChild(attack);
			});
		}

		if (data.legendary_actions.length > 0) {
			//legendary actions
			var lactions = document.createElement("div")
			lactions.style = "width:1360px;display:block";

			let ltitle = document.createElement("h2");
			ltitle.innerHTML = "Legendary Actions";
			ltitle.style= "margin-bottom:0px"
			lactions.appendChild(ltitle);

			let ldesc = document.createElement("p");
			ldesc.innerHTML = "The "+ data.name.toLowerCase() +" can take 3 legendary actions, choosing from the options below. Only one legendary action option can be used at a time and only at the end of another creature’s turn. The "+data.name.toLowerCase()+" regains spent legendary actions at the start of its turn.";
			ldesc.style= "margin-top:0px"
			lactions.appendChild(ldesc);
		
			data.legendary_actions.forEach((legendact)=> {
				let action = document.createElement("div")

				let actionname = document.createElement("h3");
				actionname.style="margin:0px";
				actionname.innerHTML = legendact.name;
				action.appendChild(actionname);

				let actiondesc = document.createElement("p");
				actiondesc.style="margin:0px;margin-bottom:10px";
				actiondesc.innerHTML = legendact.desc;
				action.appendChild(actiondesc);
				lactions.appendChild(action);
			});
		}

		statc.appendChild(leftblock);
		statc.appendChild(middleblock);

		if (data.special_abilities.length > 0)
			statc.appendChild(rightblock);
		if (data.actions.length > 0)
			statc.appendChild(attacks);
		if (data.legendary_actions.length > 0)
			statc.appendChild(lactions);
		const dd = document.getElementById("dropdown-npc-" + id);
		dd.after(statc);

	} catch (error) {
		console.error('Error fetching data:', error);
	}
}

function drawNotes(isplayer, id) {
	let dropdown = document.getElementById("dropdown-npc-"+id);
	dropdown.style ="overflow:hidden";
	while (dropdown.firstChild) {
			dropdown.removeChild(dropdown.firstChild);
	}

	id = id.replaceAll("-", " ");

	var noteresponse;
	const xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4 && xhr.status === 200) {
			noteresponse = xhr.responseText;

			let notes = document.createElement("p");
			notes.innerHTML = noteresponse;
			notes.style = "margin-left:20px;color:#C4C3D0;height:80px;width:1360px;overflow:hidden";
			dropdown.appendChild(notes);
		}
	}
	xhr.open("POST", "../scripts/getnotes.php", true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send("id="+id);

	const back= document.createElement('div');
	back.className = 'custom-button';
	back.style = "position:absolute;bottom:-15px;left:-10px;font-size:10px;width:50px;height:10px;padding:0px;";
	back.innerHTML = "BACK-";

	back.addEventListener('click', function() {
		undrawNotes(isplayer, id.replaceAll(" ", "-"));
	});

	dropdown.appendChild(back);

	const edit= document.createElement('div');
	edit.className = 'custom-button';
	edit.style = "position:absolute;bottom:-15px;left:40px;font-size:10px;width:50px;height:10px;padding:0px;";
	edit.innerHTML = "EDIT-";

	edit.addEventListener('click', function() {
		editText(id);
	});
	dropdown.appendChild(edit);
}

function editText(id) {
	id = id.replaceAll(" ", "-");
	let dropdown = document.getElementById("dropdown-npc-"+id);
	while (dropdown.firstChild) {
			dropdown.removeChild(dropdown.firstChild);
	}

	var form = document.createElement("form");

	// Create textarea element
	var textarea = document.createElement("textarea");
	textarea.id = "userTextArea";
	textarea.rows = "4";
	textarea.cols = "182";
	textarea.placeholder = "Type your text here.";

	const xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4 && xhr.status === 200) {
			textarea.value = xhr.responseText.replaceAll("<br>", "\n");
		}
	}
	xhr.open("POST", "../scripts/getnotes.php", true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send("id="+id.replaceAll("-", " "));

	// Create line break element
	var lineBreak = document.createElement("br");

	// Create submit button element
	var submitButton = document.createElement("button");
	submitButton.type = "button";
	submitButton.textContent = "Submit";
	submitButton.addEventListener("click", function() {

		// Get the value from the textarea
		var userText = textarea.value.replaceAll("\n", "<br>");

		const xhr = new XMLHttpRequest();
		xhr.open("POST", "../scripts/setnotes.php", true); // ReplaceAll with your server endpoint
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send("notes="+userText+"&id="+id.replaceAll("-", " "));
		sleep(100).then(() => {drawNotes(false, id);});
	});

	// Create paragraph element for displaying text
	var displayText = document.createElement("p");
	displayText.id = "displayText";

	// Append elements to the form
	form.appendChild(textarea);
	form.appendChild(lineBreak);
	form.appendChild(submitButton);

	form.style = "margin:20px";

	dropdown.appendChild(form);
}

function undrawNotes(isplayer, id) {
	let pos = window.pageYOffset;
	clearDropdown(isplayer, id);
	drawDropdown(isplayer, id);
	sleep(50).then(() => { window.scrollTo(0, pos); });
}

function clearDropdown(isplayer, id) {
	let dropdown;
	if (isplayer)
		dropdown = document.getElementById("dropdown-player-"+id);
	else {
		if (document.getElementById("api-statcard-" + id) != null)
			document.getElementById("api-statcard-" + id).remove();
		dropdown = document.getElementById("dropdown-npc-"+id)
	}
	dropdown.remove();
}

function addDropdown() {
	const item = document.querySelectorAll('.initiative-item');
	item.forEach(function (item) {
		let isplayer = false;
		if (item.id.substring(0,7) == "player-")
			isplayer = true;

		// determine id aswell as if clicked is player or not
		let id;
		if (isplayer)
			id = item.id.substring(7);
		else
			id = item.id.substring(4);

		var open = false;
		item.addEventListener('click', function () {
			if (open) {
				clearDropdown(isplayer, id);
				open = false;
			} else {
				drawDropdown(isplayer, id);
				open = true;
			}
		});
	});
}

var playerperms = "spectator";
var player = "none";
if (window.location.href.search("dmview") !== -1 && getCookie('loggedperms') == "dm") {
	playerperms = "dm";
} else if (getCookie("selected") != null) {
	playerperms = "player";
	player = getCookie("selected");
}

drawList();

setInterval(()=>{ drawList() },1000);