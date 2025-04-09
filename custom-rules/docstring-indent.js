const rule = 'docstring-indent'

const availableConfigs = {
  indent: {
    type: 'number',
    defaultValue: 6,
    description: 'Number of spaces required for docstring indentation'
  }
}

function run (feature, file, config = {}) {
  const errors = []
  const requiredSpaces = ' '.repeat(config.indent || 6)

  feature.children.forEach(child => {
    if (child.scenario) {
      child.scenario.steps.forEach(step => {
        if (step.docString) {
          const { location } = step.docString
          const stepIndent = step.text.match(/^\s*/)[0]
          const expectedIndent = stepIndent + requiredSpaces

          // Check opening docstring.
          const openingLine = file.lines[location.line - 1]
          if (!openingLine.startsWith(expectedIndent + '"""')) {
            errors.push({
              message: `Docstring opening """ must be indented ${config.indent} spaces`,
              rule,
              line: location.line
            })
          }

          // Check closing docstring.
          const closingLineNum = location.line + step.docString.content.split('\n').length + 1
          const closingLine = file.lines[closingLineNum - 1]
          if (!closingLine.startsWith(expectedIndent + '"""')) {
            errors.push({
              message: `Docstring closing """ must match opening indent (${expectedIndent.length} spaces total)`,
              rule,
              line: closingLineNum
            })
          }
        }
      })
    }
  })

  return errors
}

module.exports = {
  name: rule,
  run,
  availableConfigs
}
