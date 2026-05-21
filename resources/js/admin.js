document.addEventListener("DOMContentLoaded", () => {
	const replacements = [
		[/\bPosts\b/g, "Blog Posts"],
		[/\bPost\b/g, "Blog Post"],
	];

	const walk = (node) => {
		if (node.nodeType === Node.TEXT_NODE) {
			let text = node.textContent;
			replacements.forEach(([pattern, replacement]) => {
				text = text.replace(pattern, replacement);
			});
			node.textContent = text;
			return;
		}

		const skip = ["SCRIPT", "STYLE", "INPUT", "TEXTAREA"];
		if (skip.includes(node.tagName)) return;

		node.childNodes.forEach(walk);
	};

	walk(document.body);
});
