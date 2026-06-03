var e=`
[data-edit-field],
[data-edit-type="button"] {
  cursor: pointer;
}
[data-edit-field]:hover,
[data-edit-type="button"]:hover {
  outline: 2px dashed #3b82f6;
  outline-offset: 4px;
  border-radius: 2px;
}
.ple-select {
  width: 100%;
  border: 1.5px solid #d1d5db;
  border-radius: 8px;
  padding: 10px 12px;
  font: 400 14px/1.6 system-ui, sans-serif;
  color: #111;
  outline: none;
  box-sizing: border-box;
  background: #fff;
  cursor: pointer;
  transition: border-color .15s, box-shadow .15s;
}
.ple-select:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59,130,246,.15);
}
.ple-chip {
  position: fixed;
  bottom: 72px;
  right: 16px;
  z-index: 99998;
  display: flex;
  align-items: center;
  gap: 6px;
  background: #3b82f6;
  color: #fff;
  font: 600 10px/1 system-ui, sans-serif;
  letter-spacing: .08em;
  text-transform: uppercase;
  padding: 7px 11px;
  border-radius: 100px;
  box-shadow: 0 2px 12px rgba(0,0,0,.25);
  pointer-events: none;
  user-select: none;
}
.ple-backdrop {
  display: none;
  position: fixed;
  inset: 0;
  z-index: 99999;
  background: rgba(0,0,0,.55);
  backdrop-filter: blur(3px);
  -webkit-backdrop-filter: blur(3px);
  align-items: center;
  justify-content: center;
}
.ple-backdrop.open {
  display: flex;
}
.ple-modal {
  background: #fff;
  color: #111;
  border-radius: 14px;
  padding: 28px;
  width: min(620px, calc(100vw - 32px));
  box-shadow: 0 24px 64px rgba(0,0,0,.4);
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.ple-modal-label {
  font: 600 10px/1 system-ui, sans-serif;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: #6b7280;
}
.ple-input, .ple-textarea {
  width: 100%;
  border: 1.5px solid #d1d5db;
  border-radius: 8px;
  padding: 10px 12px;
  font: 400 14px/1.6 system-ui, sans-serif;
  color: #111;
  outline: none;
  box-sizing: border-box;
  resize: vertical;
  transition: border-color .15s, box-shadow .15s;
}
.ple-input:focus, .ple-textarea:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59,130,246,.15);
}
.ple-hint {
  font: 400 11px/1 system-ui, sans-serif;
  color: #9ca3af;
  margin-top: -6px;
}
.ple-rte-wrap {
  border: 1.5px solid #d1d5db;
  border-radius: 8px;
  overflow: hidden;
  transition: border-color .15s, box-shadow .15s;
}
.ple-rte-wrap:focus-within {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59,130,246,.15);
}
.ple-rte-toolbar {
  display: flex;
  gap: 2px;
  padding: 6px 8px;
  background: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
}
.ple-rte-toolbar button {
  border: none;
  background: transparent;
  border-radius: 5px;
  padding: 4px 8px;
  font: 600 13px/1 system-ui, sans-serif;
  color: #374151;
  cursor: pointer;
  transition: background .12s;
  min-width: 28px;
}
.ple-rte-toolbar button:hover { background: #e5e7eb; }
.ple-rte-toolbar button.active { background: #dbeafe; color: #1d4ed8; }
.ple-rte-toolbar .sep {
  width: 1px;
  background: #e5e7eb;
  margin: 3px 4px;
}
.ple-rte {
  min-height: 140px;
  max-height: 320px;
  overflow-y: auto;
  padding: 10px 12px;
  font: 400 14px/1.6 system-ui, sans-serif;
  color: #111;
  outline: none;
  box-sizing: border-box;
}
.ple-rte em, .ple-rte i { font-style: italic; }
.ple-rte strong, .ple-rte b { font-weight: 700; }
.ple-rte a { color: #2563eb; text-decoration: underline; }
.ple-actions {
  display: flex;
  align-items: center;
  gap: 8px;
  justify-content: flex-end;
  margin-top: 4px;
}
.ple-status {
  font: 400 12px/1 system-ui, sans-serif;
  color: #9ca3af;
  margin-right: auto;
  min-height: 14px;
}
.ple-status.ok    { color: #10b981; }
.ple-status.error { color: #ef4444; }
.ple-btn {
  border: none;
  border-radius: 8px;
  padding: 9px 18px;
  font: 600 13px/1 system-ui, sans-serif;
  cursor: pointer;
  transition: opacity .15s, background .15s;
  white-space: nowrap;
}
.ple-btn:disabled { opacity: .45; cursor: not-allowed; }
.ple-btn-cancel { background: #f3f4f6; color: #374151; }
.ple-btn-cancel:hover:not(:disabled) { background: #e5e7eb; }
.ple-btn-save { background: #3b82f6; color: #fff; }
.ple-btn-save:hover:not(:disabled) { background: #2563eb; }
[data-edit-admin] {
  cursor: pointer;
  position: relative;
}
[data-edit-admin]:hover {
  outline: 2px dashed #f59e0b;
  outline-offset: 4px;
  border-radius: 2px;
}
[data-edit-admin]::after {
  content: 'Manage in WP Admin →';
  position: absolute;
  top: 0;
  right: 0;
  background: #f59e0b;
  color: #fff;
  font: 600 9px/1 system-ui, sans-serif;
  letter-spacing: .08em;
  text-transform: uppercase;
  padding: 4px 9px;
  border-radius: 4px;
  pointer-events: none;
  opacity: 0;
  transition: opacity .15s;
  white-space: nowrap;
  z-index: 10;
}
[data-edit-admin]:hover::after {
  opacity: 1;
}
`,t={"":`None`,arrow:`Arrow →`,phone:`Phone`,mail:`Email`,"map-pin":`Map pin`,external:`External link`},n={arrow:`<svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>`,phone:`<svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16.92z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>`,mail:`<svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><polyline points="22,6 12,13 2,6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>`,"map-pin":`<svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>`,external:`<svg class="arr" width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><polyline points="15,3 21,3 21,9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><line x1="10" y1="14" x2="21" y2="3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>`},r=null;function i(){if(!document.getElementById(`wpadminbar`))return;let i=document.createElement(`style`);i.textContent=e,document.head.appendChild(i);let a=document.createElement(`div`);a.className=`ple-chip`,a.setAttribute(`aria-hidden`,`true`),a.innerHTML=`
    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
    </svg>
    Edit Mode`,document.body.appendChild(a);let o=document.createElement(`div`);o.className=`ple-backdrop`,o.setAttribute(`role`,`presentation`),o.innerHTML=`
    <div class="ple-modal" role="dialog" aria-modal="true" aria-labelledby="ple-label">
      <div id="ple-label" class="ple-modal-label"></div>
      <div id="ple-field-wrap"></div>
      <p class="ple-hint" id="ple-hint" hidden>HTML markup is supported</p>
      <div class="ple-actions">
        <span class="ple-status" id="ple-status"></span>
        <button class="ple-btn ple-btn-cancel" id="ple-cancel" type="button">Cancel</button>
        <button class="ple-btn ple-btn-save" id="ple-save" type="button">Save</button>
      </div>
    </div>`,document.body.appendChild(o);let s=document.getElementById(`ple-label`),c=document.getElementById(`ple-field-wrap`),l=document.getElementById(`ple-hint`),u=document.getElementById(`ple-status`),d=document.getElementById(`ple-save`),f=document.getElementById(`ple-cancel`);function p(){o.classList.remove(`open`),r=null,u.textContent=``,u.className=`ple-status`,c.innerHTML=``}function m(e){r=e;let n=e.dataset.editType||`text`,i=e.dataset.editLabel||e.dataset.editField||`Edit field`,a=n===`wysiwyg`,d=a?e.innerHTML.trim():n===`nl2br`?(e.innerText??e.textContent).trim():e.textContent.trim();if(s.textContent=i,l.hidden=!0,c.innerHTML=``,u.textContent=``,u.className=`ple-status`,n===`button`){let n=e.querySelector(`.btn-text`)?.textContent.trim()??``,r=e.getAttribute(`href`)??``,i=e.dataset.editIconValue??``,a=(e,t,n)=>{let r=document.createElement(`label`);r.htmlFor=e,r.className=`ple-modal-label`,r.style.display=`block`,r.style.marginBottom=`4px`,r.textContent=t;let i=document.createElement(`div`);return i.style.marginBottom=`10px`,i.appendChild(r),i.appendChild(n),i},o=document.createElement(`input`);o.id=`ple-btn-label`,o.className=`ple-input`,o.type=`text`,o.value=n;let s=document.createElement(`input`);s.id=`ple-btn-url`,s.className=`ple-input`,s.type=`url`,s.value=r;let l=document.createElement(`select`);l.id=`ple-btn-icon`,l.className=`ple-select`,Object.entries(t).forEach(([e,t])=>{let n=document.createElement(`option`);n.value=e,n.textContent=t,n.selected=e===i,l.appendChild(n)}),c.appendChild(a(`ple-btn-label`,`Button text`,o)),c.appendChild(a(`ple-btn-url`,`URL`,s)),c.appendChild(a(`ple-btn-icon`,`Icon (optional)`,l))}else if(n===`select`){let t=JSON.parse(e.dataset.editChoices||`{}`),n=e.dataset.editValue||d,r=document.createElement(`select`);r.id=`ple-field`,r.className=`ple-select`,Object.entries(t).forEach(([e,t])=>{let i=document.createElement(`option`);i.value=e,i.textContent=t,i.selected=e===n,r.appendChild(i)}),c.appendChild(r)}else if(a){let e=document.createElement(`div`);e.className=`ple-rte-wrap`;let t=document.createElement(`div`);t.className=`ple-rte-toolbar`;function n(e,t,n,r){let i=document.createElement(`button`);return i.type=`button`,i.textContent=e,i.title=t,i.addEventListener(`mousedown`,e=>{if(e.preventDefault(),n===`createLink`){let e=prompt(`Enter URL:`);e&&document.execCommand(`createLink`,!1,e)}else n===`removeLink`?document.execCommand(`unlink`,!1,null):document.execCommand(n,!1,r??null)}),i}t.appendChild(n(`B`,`Bold`,`bold`)),t.appendChild(n(`I`,`Italic`,`italic`)),t.appendChild(n(`U`,`Underline`,`underline`));let r=document.createElement(`div`);r.className=`sep`;let i=document.createElement(`div`);i.id=`ple-field`,i.className=`ple-rte`,i.contentEditable=`true`,i.innerHTML=d,e.appendChild(t),e.appendChild(i),c.appendChild(e)}else if(n===`textarea`||n===`nl2br`){let e=document.createElement(`textarea`);e.id=`ple-field`,e.className=`ple-textarea`,e.rows=6,e.value=d,c.appendChild(e)}else{let e=document.createElement(`input`);e.id=`ple-field`,e.className=`ple-input`,e.type=n===`url`?`url`:`text`,e.value=d,c.appendChild(e)}o.classList.add(`open`),requestAnimationFrame(()=>{(n===`button`?document.getElementById(`ple-btn-label`):document.getElementById(`ple-field`))?.focus()})}async function h(e,t,n,r){let i=await(await fetch(window.planetarioEditor.apiUrl,{method:`POST`,credentials:`same-origin`,headers:{"Content-Type":`application/json`,"X-WP-Nonce":window.planetarioEditor.nonce},body:JSON.stringify({post_id:e,field_name:t,field_type:n,value:r})})).json();if(!i.success)throw Error(i.message||`Save failed.`);return i.value??r}async function g(){if(!r)return;let e=r.dataset.editType||`text`,t=r.dataset.editPost||String(window.planetarioEditor?.postId||`0`),i=t===`option`?`option`:parseInt(t,10);d.disabled=!0,f.disabled=!0,u.textContent=`Saving…`,u.className=`ple-status`;try{if(e===`button`){let e=document.getElementById(`ple-btn-label`)?.value??``,t=document.getElementById(`ple-btn-url`)?.value??``,a=document.getElementById(`ple-btn-icon`)?.value??``,o=r.dataset.editLabelField,s=r.dataset.editUrlField,c=r.dataset.editIconField;if(!i&&i!==`option`||!o)throw Error(`Missing field or post ID.`);await h(i,o,`text`,e),await h(i,s,`url`,t),await h(i,c,`select`,a);let l=r.querySelector(`.btn-text`);l&&(l.textContent=e),r.href=t,r.dataset.editIconValue=a;let d=r.querySelector(`svg.arr`);d&&d.remove(),n[a]&&r.insertAdjacentHTML(`beforeend`,n[a]),document.dispatchEvent(new CustomEvent(`ple:saved`,{detail:{field:o,value:e}})),u.textContent=`Saved!`,u.className=`ple-status ok`,setTimeout(p,800);return}let t=document.getElementById(`ple-field`),a=r.dataset.editField,o=t.contentEditable===`true`?t.innerHTML:t.value;if(!i&&i!==`option`||!a)throw Error(`Missing field or post ID.`);let s=await h(i,a,e,o);if(document.dispatchEvent(new CustomEvent(`ple:saved`,{detail:{field:a,value:s}})),e===`select`){let e=JSON.parse(r.dataset.editChoices||`{}`);r.dataset.editValue=s;let t=r.querySelector(`.hero-admin-btn-label`);t&&(t.textContent=e[s]||s),u.textContent=`Saved!`,u.className=`ple-status ok`,setTimeout(p,800)}else e===`wysiwyg`?(r.innerHTML=s,u.textContent=`Saved!`,u.className=`ple-status ok`,setTimeout(p,800)):(r.dataset.editHrefPrefix!==void 0&&(r.href=r.dataset.editHrefPrefix+s),r.textContent=s,u.textContent=`Saved!`,u.className=`ple-status ok`,setTimeout(p,800))}catch(e){u.textContent=e.message||`An error occurred.`,u.className=`ple-status error`}finally{d.disabled=!1,f.disabled=!1}}d.addEventListener(`click`,g),f.addEventListener(`click`,p),o.addEventListener(`click`,e=>{e.target===o&&p()}),document.addEventListener(`keydown`,e=>{e.key===`Escape`&&o.classList.contains(`open`)&&p()}),document.querySelectorAll(`[data-edit-field]`).forEach(e=>{e.addEventListener(`click`,t=>{t.stopPropagation(),t.preventDefault(),m(e)})}),document.querySelectorAll(`[data-edit-type='button']`).forEach(e=>{e.addEventListener(`click`,t=>{t.preventDefault(),t.stopPropagation(),m(e)})}),document.querySelectorAll(`[data-edit-admin]`).forEach(e=>{e.addEventListener(`click`,t=>{t.stopPropagation(),t.preventDefault();let n=e.dataset.editAdmin,r=window.planetarioEditor?.adminUrl||`/wp-admin/`;window.open(r+n,`_blank`)})})}document.addEventListener(`DOMContentLoaded`,i);