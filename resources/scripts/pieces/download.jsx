import React, { Component } from "react"
import { toPng, toBlob } from 'html-to-image'
import { download } from 'downloadjs'
import html2canvas from 'html2canvas'

window.addEventListener('blob_create', data => {
	let html = data.detail.render
	let nodeHtml = document.createElement('div');
	nodeHtml.innerHTML = html;

	//console.log(html);
	//html = 'test'
	//let nodeOuter = document.createElement('div');
	//nodeOuter.classList.add('jumbotron','pokedex','printable');

	let nodeInner = document.createElement('div');
	nodeInner.classList.add('table');
	nodeInner.setAttribute("id", "print-png");
	//nodeInner.innerHTML = html;

	let rows = nodeHtml.getElementsByClassName('table-row')
		console.log(rows);

	Array.from(rows).forEach((row, i) => {
		let node = document.createElement('div');
		node.classList.add('jumbotron','pokedex','printable');
		//node.appendChild(nodeOuter)
		node.appendChild(nodeInner)
		node.appendChild(row)
		document.body.appendChild(node)

		//let node = document.getElementById('print-png');
			console.log(i);
			console.log(node);

		let nodeFilename = `${ data.detail.path }-${ data.detail.filename }-${ i }.png`

		toPng(node).then(function (blob) {
			//console.log(blob);
			//livewire.emit('blob_save', blob, nodeFilename);
			//node.remove()
		}).catch(function (error) {
			console.error('oops, something went wrong!', error);
			//node.remove()
		});
	});
			
			console.log('test')
})