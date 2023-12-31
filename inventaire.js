var inventaire_version = 2;

// Conception de Francois Mazerolle, 2006
// Vous pouvez utiliser et/ou modifier ce script dans un contexte non-lucratif seulement.
// Si vous d�sirez une authorisation d'usage commerciale, veuillez me contacter � l'adresse suivante: admin@maz-concept.com
//
// Conception by Francois Mazerolle, 2006
// You can use and/or modify this script for non-lucrative purposes only.
// If you need a commercial usage authorisation, please contact me at: admin@maz-concept.com 

//BUG(S) � CORRIGER:

//Note: DG = L'�l�ment d�placable (dragable), DZ= Drop Zone, zone pour lacher un DG.




//Varibles globales
var tmpNeverDebugged	= true;
var animationEnCours	= 0;			//Nombre d'animations en cours
var arrTimer			= Array(); 		//Tableau de tous les timers utilis�s


var arrItems		= Array();			//Tableau des items (images d�placeable)
var arrItemFiches	= Array();			//Tableau des fiches d'item
var arrDz			= Array();			//Tableau des Drop Zone


var FicheMarge	= 5;	//en pixel
var FicheWidth	= 300;	//en pixel

var zIndexDz		= 96;
var zIndexItemHS 	= 97;
var zIndexFiche		= 98;
var zIndexItemON 	= 99;


// class FicheItem{

//Constructeur de la classe FicheItem
function FicheItem( id, titre, description, actions, pr, qte ){
	//Variables
	this.id				= id;
	this.obj			= $("fiche_" + this.id);
	this.titre			= unescape(titre);
	this.description	= unescape(description);
	this.actions		= actions;
	this.pr				= pr;
	this.qte			= qte;
	this.x				= null; //Sera d�tect� lors de l'affichage
	this.y				= null; //Sera d�tect� lors de l'affichage
	this.width			= 0; //est d�tect� par autosize : apr�s le chargement du contenu
	this.height			= 0; //est d�tect� par autosize : apr�s le chargement du contenu
	this.isAffiche		= false;
	
	//M�thodes
	this.afficher		= FicheItem_Afficher;
	this.masquer		= FicheItem_Masquer;
	this.setPosition	= FicheItem_SetPosition;
	this.setTaille		= FicheItem_SetTaille;
	this.genSpec		= FicheItem_GenSpec;
	this.genAction		= FicheItem_GenAction;	
	this.autoSize		= FicheItem_AutoSize;
	this.modAction		= FicheItem_ModifyAction;
	//this.delAction		= FicheItem_DeleteAction;
	this.moveResize		= FicheItem_MoveResize;
	
	
	//Affecter une taille correcte � la Fiche
	this.obj.style.zIndex = zIndexFiche;
	
	//Ins�rer le contenu
	$("fiche_" + this.id + "_actions").innerHTML = this.genAction();
	$("fiche_" + this.id + "_specs").innerHTML = this.genSpec();
	$("fiche_" + this.id + "_nom").innerHTML = this.titre;
	$("fiche_" + this.id + "_description").innerHTML = this.description;
	
	//Calculer la hauteur
	this.autoSize(false);
	$("table_" + this.id).style.width	= this.width  + "px";
}


//G�n�rer les specs
function FicheItem_GenSpec(){
	var msg = '';
	msg += "PR: " + this.pr + "<br />";
	msg += "Qte: <span id=\"qte_" + this.id + "\">" + this.qte + "</span><br />";
	
	return msg;
}

//G�n�rer les actions
function FicheItem_GenAction(){
	var msg='';
	for(var i=0;i<this.actions.length;i++){
		msg += "<strong>&gt;</strong>&nbsp;";
		msg += "<a href=\"#\" onclick=\"" + this.actions[i][1] + "(" + this.id + ")\">";
		msg += this.actions[i][0] + "</a><br />";
	}
		
	return msg;
}
//Modifier une action
function FicheItem_ModifyAction(oldTech, newTech, newTxt){
	for(var i=0;i<this.actions.length;i++){
		if(this.actions[i][1] == oldTech){
			this.actions[i][0] = newTxt;
			this.actions[i][1] = newTech;
			break;
		}
	}
	
	$("fiche_" + this.id + "_actions").innerHTML = this.genAction();
}
//Effacer une action
/*
function FicheItem_DeleteAction(oldTech){
	for(var i=0;i<this.actions.length;i++){
		if(this.actions[i][1] == oldTech){
			this.actions.splice(i,1);
			return;
		}
	}
	$("fiche_" + this.id + "_specs").innerHTML = this.genSpec();
}
*/

//Afficher la fiche de l'item
function FicheItem_Afficher(){
	this.isAffiche = true;
	
	
	//D�tecter quel est le Dg associ� � cette fiche
	var dg = FindItem(this.id);
	
	//Masquer toutes les autres fiches
	for(var i=0;i<arrItemFiches.length;i++)
		if(arrItemFiches[i].id != this.id)
			arrItemFiches[i].masquer();
	
	
	//Calculer la taille r�elle de la fiche
	this.autoSize(false);
	
	
	
	//D�tecter si l'item � assez d'espace pour s'afficher vers la droite
	if ((dg.x + dg.width + 2*FicheMarge) >= this.width){
		//Afficher vers la gauche
		var dgFinalPosX		= (dg.x + dg.width) - this.width;
		var ficheStartPosX	= dg.x + dg.width + FicheMarge;
		var ficheFinalPosX	= dgFinalPosX - FicheMarge;
	}else{ 
		//Afficher vers la droite
		var dgFinalPosX		= dg.x;
		var ficheStartPosX	= dgFinalPosX - FicheMarge;
		var ficheFinalPosX	= dgFinalPosX - FicheMarge;
	}
	
	//D�tecter si l'item � assez d'espace pour s'afficher vers le bas
	if ((dg.y + dg.height + 2*FicheMarge) >= this.height){
		var dgFinalPosY		= (dg.y + dg.height) - this.height;
		var ficheStartPosY	= dg.y + dg.height + FicheMarge;
		var ficheFinalPosY	= dgFinalPosY - FicheMarge;
	}else{
		var dgFinalPosY		= dg.y;
		var ficheStartPosY	= dgFinalPosY - FicheMarge;
		var ficheFinalPosY	= dgFinalPosY - FicheMarge;
	}
	
	//Innitialiser l'�l�ment � sa position de d�part
	var ficheFinalWidth = this.width;
	var ficheFinalHeight = this.height;
	
	
	//D�marrer l'animation
	dg.obj.style.zIndex = zIndexItemON;
	this.setTaille(0, 0);
	this.setPosition(ficheStartPosX, ficheStartPosY);
	this.moveResize(this.id, ficheFinalPosX, ficheFinalPosY, ficheFinalWidth, ficheFinalHeight, true, 10, 20);
	dg.moveTo(dg.id, dgFinalPosX, dgFinalPosY, true, 10, 20);
	
}




//Masquer la fiche de l'item
function FicheItem_Masquer(){
	
	if (this.isAffiche){
		this.isAffiche = false;
		var dg			= FindItem(this.id);
		
		//D�placer l'item vers sa position dans son Dz de base
		this.autoSize(false);
		this.moveResize(this.id, dg.baseDz.x, dg.baseDz.y, 0, 0, false, 10, 20);
		
		var pos1 = new centerItemIntoItem(dg, dg.baseDz);
		dg.moveTo(dg.id, pos1.x, pos1.y, false, 10, 20);
		
	}
}


function FicheItem_MoveResize(ficheId, toX, toY, toW, toH, showContent, interval, moveSteps, firstMove) {
	
	//Trouver le Dg(this) 
	var fiche = FindItemFiche(ficheId);
	
	if(firstMove==null){
		animationEnCours++;
		fiche.obj.style.display = "";
	}
	
	
	//�tablir le 'pas' (Nombre de pixel d�plac� par interval)
	stepX			= Math.round( (fiche.x - toX) / moveSteps );
	stepY			= Math.round( (fiche.y - toY) / moveSteps );
	stepW			= Math.round( (fiche.width  - toW) / moveSteps );
	stepH			= Math.round( (fiche.height - toH) / moveSteps );
	
	if(moveSteps>0){
		fiche.setPosition(	(fiche.x - stepX), (fiche.y - stepY));
		fiche.setTaille(	(fiche.width - stepW), (fiche.height - stepH));
	}
	moveSteps--;
	
	//Si le d�placement est termin�, placer pr�cis�ment l'objet et stoper le timer, sinon, continuer le d�placement
	if(moveSteps==0){
		clearTimeout(arrTimer['fiche'+ficheId]);
		
		fiche.setPosition(toX, toY);
		fiche.setTaille(toW, toH);
		if (!showContent)
			fiche.obj.style.display = "none";
		animationEnCours--;
		
	}else{
		arrTimer['fiche'+ficheId] = setTimeout("FicheItem_MoveResize("+ficheId+", "+toX+", "+toY+", "+toW+", "+toH+", "+showContent+", "+interval+", "+moveSteps+", false)", interval);
	}
}


function FicheItem_AutoSize(visible){
	
	this.obj.style.display = "";
	this.width	= FicheWidth;
	this.height = parseInt($("table_" + this.id).offsetHeight);
	
	this.setTaille(this.width, this.height);
	
	if(!visible)
		this.obj.style.display = "none";
	
}

function FicheItem_SetPosition(x, y){
	this.x = x;
	this.y = y;
	
	this.obj.style.left	= this.x + "px";
	this.obj.style.top	= this.y + "px";
}

function FicheItem_SetTaille(width, height){
	this.width	= width;
	this.height	= height;
	
	this.obj.style.width				= this.width  + "px";
	this.obj.style.height				= this.height + "px";
}



// }















//class Item{

function Item( id, baseDz, equipType){
	
	//Variables
	this.id		= id;
	
	var dzobj = FindDz(baseDz);
	if (dzobj==null)
		alert("Impossible de trouver le Dz de l'item " + this.id + ", soit le dz nomm�: '" + baseDz + "'");
		
	this.baseDz			= dzobj;
	this.obj			= $('dg_' + this.id);
	this.x				= null; //sett� par la fonction placer
	this.y				= null; //sett� par la fonction placer
	this.width			= parseInt(this.obj.style.width);
	this.height			= parseInt(this.obj.style.height);
	this.equipType		= equipType;
	
	
	//M�thodes
	this.setPosition	= Item_SetPosition;
	this.moveTo			= Item_MoveTo;
	
	//Placer l'item dans son Dz de base
	var pos1 = new centerItemIntoItem(this, this.baseDz);
	this.setPosition(pos1.x, pos1.y);
	this.obj.style.zIndex = zIndexItemHS;
	$("tableimg_" + this.id).style.height = this.height + "px";
	
	//Placer les �v�nements d'�coutes
	if (document.attachEvent)//IE
		this.obj.attachEvent ('onclick', AfficherFiche);
		
	else
		this.obj.addEventListener("click", AfficherFiche, true);
	this.obj.idOnly = this.id;
}

function Item_SetPosition(x, y){
	this.x = x;
	this.y = y;
	
	this.obj.style.left	= this.x + "px";
	this.obj.style.top	= this.y + "px";
}

function Item_MoveTo(dgId, toX, toY, stayOnTopAfter, interval, moveSteps, firstMove) { //Fonction qui "Glisse" un objet d'un endroit vers un autre Dz
	
	//Trouver le Dg(this) 
	var dg = FindItem(dgId);
	
	if(firstMove==null)
		animationEnCours++;
	
	
	//�tablir le 'pas' (Nombre de pixel d�plac� par interval)
	dg_stepX			= Math.round( (dg.x - toX) / moveSteps );
	dg_stepY			= Math.round( (dg.y - toY) / moveSteps );
	
	if(moveSteps>0)
		dg.setPosition(	(dg.x - dg_stepX), (dg.y - dg_stepY));
	moveSteps--;
	
	//Si le d�placement est termin�, placer pr�cis�ment l'objet et stoper le timer, sinon, continuer le d�placement
	if(moveSteps==0){
		clearTimeout(arrTimer['dg'+dgId]);
		
		dg.setPosition(toX, toY);
		animationEnCours--;
		if(stayOnTopAfter)
			dg.obj.style.zIndex = zIndexItemON;
		else
			dg.obj.style.zIndex = zIndexItemHS;
		
	}else{
		arrTimer['dg'+dgId] = setTimeout("Item_MoveTo("+ dgId+", "+toX+", "+toY+", "+stayOnTopAfter+", "+interval+", "+moveSteps+", false)", interval);
	}
}


//}












//class Dz{

function Dz( id ){
	
	
	//Variables
	this.id			= id;
	this.obj			= $("dz_" + this.id);
	this.x				= 0;	//est calcul� par getPos()
	this.y				= 0;	//est calcul� par getPos()
	this.width			= parseInt(this.obj.style.width);
	this.height			= parseInt(this.obj.style.height);
	
	//M�thodes
	this.getPos			= Dz_GetPos;
	
	//Calculer la position de la Dz
	this.getPos();
	this.obj.style.zIndex = zIndexDz;
}

function Dz_GetPos(){
	this.x = 0;
	this.y = 0;
	
	parentObj = this.obj;
	if (parentObj.offsetParent){
		while (parentObj.offsetParent)
		{
			this.x += parentObj.offsetLeft
			this.y += parentObj.offsetTop
			parentObj = parentObj.offsetParent;
		}
	}else if (parentObj.x){
		this.x += parentObj.x;
		this.y += parentObj.y;
	}
}


//}














//M�thodes qui retourne un objet s'il est pr�sent dans le tableau des objets (et ce en fonction de leur ID d'item)
function FindDz( id ){
	for(var i=0;i<arrDz.length;i++)
		if(arrDz[i].id == id)
			return arrDz[i];
	return null;
}

function FindItem( id ){
	for(var i=0;i<arrItems.length;i++)
		if(arrItems[i].id == id)
			return arrItems[i];
	return null;
}

function FindItemFiche( id ){
	for(var i=0;i<arrItemFiches.length;i++)
		if(arrItemFiches[i].id == id)
			return arrItemFiches[i];
	return null;
}



//class centerItemIntoItem{

function centerItemIntoItem(item, container){
	if (typeof(item)!='object')
		alert('Param#1 n\'item pas un object');
	if (typeof(container)!='object')
		alert('Param#2 n\'item pas un object');
		
	this.x = container.x;
	this.y = container.y;
	
	if (item.width<container.width) //Si l'item peut-�tre contenu horizontalement
		this.x += (container.width/2)-(item.width/2);
		
		
	if (item.height<container.height) //Si l'item peut-�tre contenu horizontalement
		this.y += (container.height/2)-(item.height/2);
		
		
}

// }









// CALLED METHOD. M�thode apell� par des Event Handler (elle servent d'interm�diaire afin d'�viter des fuck de 'this'
function AfficherFiche(e){
	if (animationEnCours!=0){
		alert("Veuillez patienter, " + animationEnCours + " animation(s) en cours...");
		return;
	}
	
	
	//D�terminer l'ID de l'item
	// "target" for Mozilla, Netscape, Firefox et al. ; "srcElement" for IE
	var id = (e["srcElement"]) ? e["srcElement"]["idOnly"] : e["target"]["idOnly"];
	
	
	//var arr = this.id.split(new RegExp("[_]", "g"));
	var fiche = FindItemFiche(id);
	if (fiche.isAffiche)
		fiche.masquer();
	else
		fiche.afficher();
}

//R�-aligner tous les �l�ments de la page avec leurs baseDz
function ReplaceElements(){
	//Actualiser la position X, Y des DZ
	for(var i=0;i<arrDz.length;i++)
		arrDz[i].getPos();
	
	//R�aligner tout les items
	for(var i=0;i<arrItems.length;i++){
		var pos1 = new centerItemIntoItem(arrItems[i].obj, arrItems[i].baseDz);
		arrItems[i].setPosition(pos1.x, pos1.y);
	}
		
	//Masquer toutes les fiches
	for(var i=0;i<arrItemFiches.length;i++)
		arrItemFiches[i].masquer();
}














function debugAllArr(){
	var msg = '';
	
	msg += "\n<br />\n<br /><strong>---- dz (length: " + arrDz.length + "):</strong>\n<br />";
	for(var i=0;i<arrDz.length;i++)
			msg += "[" + i + "]=>\n<br />" + debugObject(arrDz[i]) + "\n<br />";
	
	msg += "\n<br />\n<br /><strong>---- items (length: " + arrItems.length + "):</strong>\n<br />";
	for(var i=0;i<arrItems.length;i++)
			msg += "[" + i + "]=>\n<br />" + debugObject(arrItems[i]) + "\n<br />";
	
	msg += "\n<br />\n<br /><strong>---- fiches (length: " + arrItemFiches.length + "):</strong>\n<br />";
	for(var i=0;i<arrItemFiches.length;i++)
			msg += "[" + i + "]=>\n<br />" + debugObject(arrItemFiches[i]) + "\n<br />";
	
	$('debug').innerHTML = msg;
}


function debugObject(obj){
	var h = $H(obj);
	var arrK = h.keys();
	var arrV = h.values();
	
	var msg = "";
	for(var i=0;i<arrK.length;i++)
		msg += arrK[i] + " = '" + arrV[i] + "'\n<br />";
	return msg;
}



















//Replacer les �l�ments si la taille de la page change
if (document.attachEvent)//IE
	window.attachEvent ('onresize', ReplaceElements);
else
	window.addEventListener("resize", ReplaceElements, true);