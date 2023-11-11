function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

function clearSuggestions() {
	const item = document.querySelectorAll('.selectable');
	item.forEach(function (item) {
		item.remove();
	});
}

function drawSuggestions() {
	let counter = 0;
	getSuggestions();
	clearSuggestions();
	matches.forEach((value) => {
		if (counter < 5 && value[1].toLowerCase() != textarea.value.toLowerCase()) {
			let match = document.createElement("div");
			match.className = "selectable";
			match.innerHTML = value[1];

			match.addEventListener('click', function() {
				textarea.value = value[1];
				updateSuggestions();
			});

			let text = document.getElementById("api-enter");
			text.after(match);

			counter++;
		}
	});
}

function fetchData() {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    resolve(xhr.responseText);
                } else {
                    reject(new Error(`HTTP request failed with status ${xhr.status}`));
                }
            }
        };

        xhr.open('GET', "https://www.dnd5eapi.co/api/monsters/" + document.getElementById("api-name").value, true);
        xhr.send();
    });
}

// Example of using fetchData with async/await
async function fetchDataAction() {
    try {
        const data = await fetchData();
        return JSON.parse(data);
        // Continue with the logic that depends on the XMLHttpRequest
        // Call your function or perform other actions here
    } catch (error) {
        console.error('Error fetching data:', error);
        // Handle the error as needed
    }
}

function fetchDataNames() {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    resolve(xhr.responseText);
                } else {
                    reject(new Error(`HTTP request failed with status ${xhr.status}`));
                }
            }
        };

        xhr.open('GET', "../scripts/tempnamelist.php", true);
        xhr.send();
    });
}

// Example of using fetchData with async/await
async function fetchDataActionNames() {
    try {
        const data = await fetchDataNames();

        let namelist = data.split("/");

        return namelist;
        // Continue with the logic that depends on the XMLHttpRequest
        // Call your function or perform other actions here
    } catch (error) {
        console.error('Error fetching data:', error);
        // Handle the error as needed
    }
}
async function updateHitdiceNotes() {

	let data = await fetchDataAction();

	let namelist = await fetchDataActionNames();

	let name = "";
	let dupes = 1;
	for (let i = 0; i < namelist.length; i++) {
		let value = namelist[i];
		if (data.name.toLowerCase() + " " + (dupes) == value.toLowerCase()) {
			dupes++;
			i = 0;
		}
	}
	name = data.name + " " + dupes;


	let dxt = {
		value: data.dexterity,
		mod: Math.floor((data.dexterity - 10) / 2),
		save: Math.floor((data.dexterity - 10) / 2)
	}

	// display max hp etc
	let maxhp = document.createElement("div");
	maxhp.innerHTML = "default max hp "+data.hit_points+" hp<br>max hp dice : "+data.hit_points_roll+" hp<br>initiative modifier: "+dxt.mod;
	document.getElementById("hp2").value = data.hit_points;
	document.getElementById("maxhp2").value = data.hit_points;
	document.getElementById("name2").value = name;
	maxhp.className = "dicehp";
	document.getElementById("api-header").after(maxhp);
}

function updateSuggestions() {
	for (let i = 0; i < matches.length; i++) {
		value = matches[i];
		if (textarea.value.toLowerCase() == value[1].toLowerCase()) {
			let name = document.getElementById("api-name");
			name.value = value[0];

			updateHitdiceNotes();
			break;
		} else {
			const item = document.querySelectorAll('.dicehp');
			item.forEach(function (item) {
				item.remove();
			});

			document.getElementById("api-name").value = "";
			document.getElementById("name2").value = "";
			document.getElementById("hp2").value = "";
			document.getElementById("maxhp2").value = "";
		}
	}
	drawSuggestions();

}

var monsters = [];
function getMonsters() {
	const xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4 && xhr.status === 200) {
			const data = JSON.parse(xhr.responseText);
			data.results.forEach((result) => {
			    // Access the index and name properties
			    const record = [result.index, result.name];
			    monsters.push(record);
			});
		}
	}
	xhr.open("GET", "https://www.dnd5eapi.co/api/monsters/", true); // Replace with your server endpoint
	xhr.send();
}

var matches = [];
function getSuggestions() {
	matches = [];
	if (textarea.value == "")
		return;

	monsters.forEach((value) => {
		// loose search
		if (value[1].toLowerCase().search(textarea.value.toLowerCase()) != -1)
			matches.push(value);
		/* STRICT SEARCH
		if (record[1].substring(0,textarea.value.length).toLowerCase() == textarea.value.toLowerCase() && record[1].toLowerCase() != textarea.value.toLowerCase())
			matches.push(record);*/

	});
}

getMonsters();

document.addEventListener('DOMContentLoaded', function () {

	var button = document.getElementById("api-submit");
	button.addEventListener('click', async function () {
		// we post here
		if (document.getElementById("api-name").value != "" && document.getElementById("name2").value != "" && document.getElementById("hp2").value != ""
			&& document.getElementById("maxhp2").value != "" && document.getElementById("init2").value != "") {

			let npcname = document.getElementById("name2").value;
			let npchp = document.getElementById("hp2").value;
			let npcmaxhp = document.getElementById("maxhp2").value;
			let npchidden = document.getElementById("hidden2").checked;
			let npcinitiative = document.getElementById("init2").value;
			let npcrelationship = document.getElementById("relationship2").value;
			let npcnotes = "No notes added.";
			let api = document.getElementById("api-name").value;

			const data = await fetchDataAction();

			// calc armor class
			let ac = data.armor_class[0].value
			// calc passives

			let int = {
				value: data.intelligence,
				mod: Math.floor((data.intelligence - 10) / 2),
				save: Math.floor((data.intelligence - 10) / 2)
			}

			let wis = {
				value: data.wisdom,
				mod: Math.floor((data.wisdom - 10) / 2),
				save: Math.floor((data.wisdom - 10) / 2)
			}

			let passiveperc = wis.value;
			let passiveinsi = wis.value;
			let passiveinve = int.value;

			data.proficiencies.forEach((value)=>{
				if (value.proficiency.index == "skill-perception") {
					passiveperc = value.value + 10;
				} else if (value.proficiency.index == "skill-insight") {
					passiveinsi = value.value + 10;
				} else if (value.proficiency.index == "skill-investigation") {
					passiveinve = value.value + 10;
				}
			});

			// calc dv
			let dv = "null";
			if (data.senses.darkvision != undefined)
				dv = data.senses.darkvision.substring(0,data.senses.darkvision.search(" "));

			const xhr = new XMLHttpRequest();
			xhr.open("POST", "../scripts/apijoin.php", true); // ReplaceAll with your server endpoint
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.send("name="+npcname+"&hp="+npchp+"&maxhp="+npcmaxhp+"&hidden="+npchidden+"&init="+npcinitiative+"&relationship="+
				npcrelationship+"&notes="+npcnotes+"&api="+api+"&ac="+ac+"&passpc="+passiveperc+"&passis="+passiveinsi+
				"&passiv="+passiveinve+"&dv="+dv);
			/*
				$id = $_POST["name"];
				$hp = $_POST["hp"];
				$maxhp = $_POST["maxhp"];
				$hidden = isset($_POST["hidden"]) ? "1" : "0";
				$init = $_POST["init"];
				$relationship = $_POST["relationship"];
				$notes = $_POST["notes"];
				$api = $_POST["api"];

			*/
		}
	});
});

var textarea = document.createElement("textarea");
textarea.id = "api-enter";
textarea.rows = "1";
textarea.cols = "32";
textarea.placeholder = "Enter creature type";
textarea.required = true;

document.getElementById("api-form").appendChild(textarea);
document.getElementById("api-form").appendChild(document.createElement("br"));
textarea.addEventListener('input', function() { updateSuggestions(); });

/*

// name, size, type, alignment, ac handled
			let notes = "[Name: " + data.name + ", size: " + data.size + ", type: " + data.type + ", alignment" + data.alignment + "] AC:";
			data.armor_class.forEach((value, i)=>{
				if (i != 0)
					notes+=", ";
				notes+="(" + value.value + ", " + value.type + ")";
			});

			notes+="; Hit dice: " + data.hit_dice + ", speed: ";

			let str = {
				value: data.strength,
				mod: Math.floor((data.strength - 10) / 2),
				save: Math.floor((data.strength - 10) / 2)
			}

			let dxt = {
				value: data.dexterity,
				mod: Math.floor((data.dexterity - 10) / 2),
				save: Math.floor((data.dexterity - 10) / 2)
			}

			let con = {
				value: data.constitution,
				mod: Math.floor((data.constitution - 10) / 2),
				save: Math.floor((data.constitution - 10) / 2)
			}

			let int = {
				value: data.intelligence,
				mod: Math.floor((data.intelligence - 10) / 2),
				save: Math.floor((data.intelligence - 10) / 2)
			}

			let wis = {
				value: data.wisdom,
				mod: Math.floor((data.wisdom - 10) / 2),
				save: Math.floor((data.wisdom - 10) / 2)
			}

			let cha = {
				value: data.charisma,
				mod: Math.floor((data.charisma - 10) / 2),
				save: Math.floor((data.charisma - 10) / 2)
			}

			var proficiency = "none";

			data.proficiencies.forEach((value)=>{
				if (value.proficiency.index.search("saving-throw") != -1) {
					switch(value.proficiency.index.substring(13)) {
					case "str":
						str.save=value.value;
						break;
					case "dex":
						dxt.save=value.value;
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
					console.log(value.proficiency.index + " : " + value.value);
				} else if (value.proficiency.index.search("skill-") != -1) {
					if (proficiency != "none") {
						proficiency +=", "
					} else {
						proficiency = "";
					}
					proficiency += value.proficiency.name.substring(7) + " +" + value.value;
				} else {
					if (proficiency != "none") {
						proficiency +=", "
					} else {
						proficiency = "";
					}
					proficiency += value.proficiency.name;
				}
			});

			// handle speed here
			notes+= "\nSTR:"+str.value+"("+str.mod+") DEX:"+dxt.value+"("+dxt.mod+") CON:"+con.value+"("+con.mod+") INT:"+int.value+"("+int.mod+") WIS:"+wis.value+"("+wis.mod+") CHA:"+cha.value+"("+cha.mod+")"+
				"SAVING THROWS: STR("+str.save+") DEX("+dxt.save+") CON("+con.save+") INT("+int.save+") WIS("+wis.save+") CHA("+cha.save+")";
			notes+= "\nProficiencies: " + proficiency;

			notes+=" Vulnerabilities: ";
			vlnb = "none";
			data.damage_vulnerabilities.forEach((value) => {
				if (vlnb != "none")
					vlnb+=", ";
				else
					vlnb = "";
				vlnb+=value;
			});
			notes+=vlnb;

			notes+=" Resistances: ";
			res = "none";
			data.damage_resistances.forEach((value) => {
				if (res != "none")
					res+=", ";
				else
					res = "";
				res+=value;
			});
			notes+=res;

			notes+=" Immunities: ";
			imm= "none";
			data.damage_immunities.forEach((value) => {
				if (imm != "none")
					imm+=", ";
				else
					imm = "";
				imm+=value;
			});
			notes+=imm;

			notes+=" Condition Immunities: ";
			cdimm= "none";
			data.condition_immunities.forEach((value) => {
				if (cdimm != "none")
					cdimm+=", ";
				else
					cdimm = "";
				cdimm+=value;
			});
			notes+=cdimm;
			note+=

			console.log("npc being created with name: " + npcname + ", hp: " + npchp + ", maxhp: "+ npcmaxhp +", npchidden: " + npchidden + ", initiative: " + npcinitiative
				+ ", relationship: " + npcrelationship + ", and notes:")
			console.log(notes);

*/