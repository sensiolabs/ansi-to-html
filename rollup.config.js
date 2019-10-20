import resolve from "rollup-plugin-node-resolve";
import commonjs from "rollup-plugin-commonjs";
import json from "rollup-plugin-json";
import { terser } from "rollup-plugin-terser";

function createConfig(entry, out, { minify = false } = {}) {
  return [
    {
      input: entry,
      output: { file: `${out}.js`, format: "esm" },
      plugins: [commonjs(), resolve(), json(), minify ? terser() : null]
    }
  ];
}

export default [
  ...createConfig(`./src/ansi_to_html.js`, "dist/ansi_to_html.esm"),
  ...createConfig(`./src/ansi_to_html.js`, "dist/ansi_to_html.esm.min", {
    minify: true
  })
];
