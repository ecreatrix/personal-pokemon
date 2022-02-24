import React, { Component } from "react"
import { toPng, toBlob } from 'html-to-image'
import { download } from 'downloadjs'
import html2canvas from 'html2canvas'

window.addEventListener('blob_create', data => {
	let html = data.detail.render
	//html = 'test'
	let filename = `${ data.detail.filename }.png`
	let node = document.createElement('div');
	//node.classList.add('d-none');
	node.setAttribute("id", "print-png");
	node.innerHTML = html;
	document.body.appendChild(node)
	node = document.getElementById('print-png');

	toPng(node).then(function (blob) {
		console.log(blob);
		livewire.emit('blob_save', blob);
		node.remove()
	}).catch(function (error) {
		console.error('oops, something went wrong!', error);
		node.remove()
	});
})
