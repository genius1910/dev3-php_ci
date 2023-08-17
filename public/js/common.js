function getSum(total, num) {
  return total + Math.round(num);
}

function number_format(number, decimals, dec_point, thousands_sep) {
  decimals = typeof decimals !== 'undefined' ? decimals : 0;
  dec_point = typeof dec_point !== 'undefined' ? dec_point : '.';
  thousands_sep = typeof thousands_sep !== 'undefined' ? thousands_sep : ',';
  if (isNaN(number)) {
    number = 0;
  }
  const parts = Number(number).toFixed(decimals).toString().split('.');
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);
  if (parts.join(dec_point) == '-0')
    return 0;
  else
    return parts.join(dec_point);
}

function getDiffMargin(first, second) {
  const percentage = (first - second) / first * 100;

  return number_format(percentage, 2);
}

function getDiffPercentage(a, b) {
  if (parseFloat(a))
    return ((b - a) / a) * 100;
  return 0;
}

function getDiffPercentageFormatted(first, second) {
  const percentage = getDiffPercentage(first, second);

  return number_format(percentage, 2);
}

function twoYearsTargetTable(data) {
  let {
    year0,
    year1,
    year2,
    year0table,
    year1table,
    year2table,
    yearstartmonth,
    salesTargetForLastThreeYear,
    G_MarginOk,
    G_MarginGood,
    G_kpithreshold1,
    G_kpithreshold2,
    year0total,
    year1total,
    year2total,
    year0data,
    year1data,
    year2data,
  } = data;
  let percentage = 0;
  let colourPercentage = 0;
  let runningTotalYear0 = 0;
  let class0 = "";
  let totalmonthlysalespc2 = 0;
  let stotalmonthlysalespc2 = 0;
  G_MarginOk = G_MarginOk ?? 0;
  G_MarginGood = G_MarginGood ?? 0;
  G_kpithreshold1 = G_kpithreshold1 ?? 0;
  G_kpithreshold2 = G_kpithreshold2 ?? 0;

  let twoyearstargettable = `<tr>
      <td><b>` + year0 + `</b></td>`
    + year0table +
    `</tr>
    <tr class="">
      <td><div style="margin-top: -4px;">Target</div></td>`;
  let months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
  for (let i = 1; i < yearstartmonth; i++) {
    let tmp = months.shift();
    months.push(tmp);
  }

  months.forEach(i => {
    let pre = '';
    if (i < 10)
      pre = '0';
    if (salesTargetForLastThreeYear['monthlysalespc'] && salesTargetForLastThreeYear['monthlysalespc'][year0 + pre + i]) {
      if (salesTargetForLastThreeYear['monthlysalespc'][year0 + pre + i] < G_MarginOk) {
        class0 = "bg-red-full";
      } else if (salesTargetForLastThreeYear['monthlysalespc'][year0 + pre + i] >= G_MarginOk && salesTargetForLastThreeYear['monthlysalespc'][year0 + pre + i] < G_MarginGood) {
        class0 = "bg-yellow-full";
      } else if (salesTargetForLastThreeYear['monthlysalespc'][year0 + pre + i] >= G_MarginGood) {
        class0 = "bg-green-full";
      }
    }
    if (!salesTargetForLastThreeYear[year0 + pre + i]) {
      class0 = "bg-green-full";
    }
    if (i > new Date().getMonth() + 1) {
      class0 = "";
    }
    twoyearstargettable += `<td>` + (salesTargetForLastThreeYear[year0 + pre + i] ? number_format(salesTargetForLastThreeYear[year0 + pre + i]) : '0') + `</td>`;
  });

  if (salesTargetForLastThreeYear[year0]) {
    let totalTar0 = salesTargetForLastThreeYear[year0].reduce((a, b) => a + parseFloat(b), 0);
    let totalmonthlysalespc0 = (year0total / totalTar0) * 100;
    if (totalmonthlysalespc0 < G_kpithreshold1) {
      class0 = "bg-red";
    } else if (totalmonthlysalespc0 >= G_kpithreshold1 && totalmonthlysalespc0 < G_kpithreshold2) {
      class0 = "bg-yellow";
    } else if (totalmonthlysalespc0 >= G_kpithreshold2) {
      class0 = "bg-green";
    }
  }
  if (!salesTargetForLastThreeYear[year0]) {
    class0 = "bg-green";
  }
  twoyearstargettable += `<td>` + ((salesTargetForLastThreeYear[year0] != undefined && salesTargetForLastThreeYear[year0] != null) ? number_format(salesTargetForLastThreeYear[year0].reduce(getSum, 0)) : '0') + `</td></tr>`;
  twoyearstargettable += `<tr class="border-target"><td>%</td>`;

  year0data = year0data.slice(1, -1).split(',');
  year2data = year2data.slice(1, -1).split(',');
  for (let k = 0; k < months.length; k++) {
    let i = months[k];
    pre = '';
    if (i < 10) {
      pre = '0';
    }
    if (yearstartmonth > new Date().getMonth() + 1 && new Date().getMonth() + 1 < i && i < yearstartmonth) {
      twoyearstargettable += '<td></td>';
    } else if (yearstartmonth <= new Date().getMonth() + 1 && (i < yearstartmonth || new Date().getMonth() + 1 < i)) {
      twoyearstargettable += '<td></td>';
    } else if (salesTargetForLastThreeYear[year0 + pre + i] != undefined && salesTargetForLastThreeYear[year0 + pre + i] != null) {
      percentage = 0;
      if (parseFloat(salesTargetForLastThreeYear[year0 + pre + i])) {
        percentage = (year0data[i - 1] / salesTargetForLastThreeYear[year0 + pre + i]) * 100;
      }
      colourPercentage = percentage;
      percentage -= 100;
      let percentagePrint = number_format(percentage);
      class0 = "";
      if (colourPercentage < parseFloat(G_kpithreshold1)) {
        class0 = "bg-red-full";
      } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
        class0 = "bg-yellow-full";
      } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
        class0 = "bg-green-full";
      } else {
        class0 = "bg-green-full";
      }
      twoyearstargettable += '<td class="' + class0 + '">' + percentagePrint + '%</td>';
    } else {
      twoyearstargettable += '<td class="bg-green-full">0%</td>';
    }
  }

  if (salesTargetForLastThreeYear[year0]) {
    let totalSales0 = year0data.reduce((a, b) => a + parseFloat(b), 0);
    let totalTarget0 = salesTargetForLastThreeYear[year0].reduce((a, b) => a + parseFloat(b), 0);
    let totalPercentage = 0;
    if (parseFloat(totalTarget0)) {
      totalPercentage = (totalSales0 / totalTarget0) * 100;
    }
    colourPercentage = totalPercentage;
    totalPercentage -= 100;
    let totalPercentagePrint = number_format(totalPercentage);
    class0 = "";
    if (colourPercentage < parseFloat(G_kpithreshold1)) {
      class0 = "bg-red-full";
    } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
      class0 = "bg-yellow-full";
    } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
      class0 = "bg-green-full";
    } else {
      class0 = "bg-green-full";
    }
    twoyearstargettable += '<td class="' + class0 + '">' + totalPercentagePrint + '%</td>';
  } else {
    twoyearstargettable += '<td class="bg-green-full">0%</td>';
  }

  twoyearstargettable += `
    </tr>
    <tr>	
        <td><b>` + year0 + ` Cml.</b></td>`;
  let runningTotal = 0;
  year0data.forEach(item => {
    runningTotal += parseFloat(item);
    twoyearstargettable += '<td><b>' + number_format(runningTotal) + '</b></td>';
  });
  twoyearstargettable += '<td><b>' + number_format(year0data.reduce((a, b) => a + parseFloat(b), 0)) + '</b></td>';
  twoyearstargettable += `
    </tr>
    <tr class="">
        <td><div style="margin-top: -4px;">Target</div></td>`;

  let runningTotalTarget = 0;
  for (let i of months) {
    pre = '';
    if (i < 10) {
      pre = '0';
    }
    runningTotalTarget += parseFloat(salesTargetForLastThreeYear[year0 + pre + i]);
    twoyearstargettable += '<td>' + number_format(runningTotalTarget ? runningTotalTarget : 0) + '</td>';
  }

  twoyearstargettable += '<td>' + (salesTargetForLastThreeYear[year0] ? number_format(salesTargetForLastThreeYear[year0].reduce((a, b) => a + parseFloat(b), 0)) : '0') + '</td>';
  twoyearstargettable += `
    </tr>
    <tr class="border-target">
        <td>%</td>`;

  year1data = year1data.slice(1, -1).split(',');
  runningTotal = 0;
  runningTotalTarget = 0;
  for (let i of months) {
    percentage = 0;
    colourPercentage = 0;
    let pre = '';
    if (i < 10)
      pre = '0';
    runningTotal += parseFloat(year0data[i - 1]);
    runningTotalTarget += parseFloat(salesTargetForLastThreeYear[year0 + pre + i]);
    if (runningTotalTarget > 0 && runningTotal > 0) {
      percentage = 0;
      if (parseFloat(runningTotalTarget)) {
        percentage = (runningTotal / runningTotalTarget) * 100;
      }
      colourPercentage = percentage;
      percentage -= 100;
    }
    let percentagePrint = number_format(percentage);
    class0 = "";
    if (colourPercentage < parseFloat(G_kpithreshold1)) {
      class0 = "bg-red-full";
    } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
      class0 = "bg-yellow-full";
    } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
      class0 = "bg-green-full";
    } else {
      class0 = "bg-green-full";
    }

    twoyearstargettable += '<td class="' + class0 + '">' + percentagePrint + '%</td>';
  }

  if (salesTargetForLastThreeYear[year0]) {
    let totalSales0 = year0data.reduce((a, b) => a + parseFloat(b), 0);
    let totalTarget0 = salesTargetForLastThreeYear[year0].reduce((a, b) => a + parseFloat(b), 0);
    let totalPercentage = 0;
    if (parseFloat(totalTarget0)) {
      totalPercentage = (totalSales0 / totalTarget0) * 100;
    }
    colourPercentage = totalPercentage;
    totalPercentage -= 100;
    let totalPercentagePrint = number_format(totalPercentage);
    class0 = "";
    if (colourPercentage < parseFloat(G_kpithreshold1)) {
      class0 = "bg-red-full";
    } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
      class0 = "bg-yellow-full";
    } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
      class0 = "bg-green-full";
    } else {
      class0 = "bg-green-full";
    }
    twoyearstargettable += '<td class="' + class0 + '">' + totalPercentagePrint + '%</td>';
  } else {
    twoyearstargettable += '<td class="bg-green-full">0%</td>';
  }

  twoyearstargettable += `
    </tr>
    <tr>
        <td><b>` + year0 + `</b></td>`
    + year0table + `
    </tr>
    <tr>
        <td><b>` + year1 + `</b></td>
        ` + year1table + `
    </tr>
    <tr>
        <td><div style="margin-top: -4px;">%</div></td>`;

  months.forEach(function (i) {
    --i;
    if (yearstartmonth > new Date().getMonth() + 1 && new Date().getMonth() + 1 <= i && i < yearstartmonth - 1) {
      twoyearstargettable += '<td></td>';
    } else if (yearstartmonth <= new Date().getMonth() + 1 && (i < yearstartmonth - 1 || new Date().getMonth() + 1 <= i)) {
      twoyearstargettable += '<td></td>';
    } else {
      percentage = 0;
      if (parseFloat(year1data[i])) {
        percentage = (year0data[i] / year1data[i]) * 100;
      }

      colourPercentage = percentage;
      percentage -= 100;

      class0 = "";
      if (colourPercentage < parseFloat(G_kpithreshold1)) {
        class0 = "red-arrow fa fa-arrow-down";
      } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
        class0 = "yellow-arrow fa fa-arrow-right";
      } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
        class0 = "green-arrow fa fa-arrow-up";
      } else {
        class0 = "green-arrow fa fa-arrow-up";
      }

      twoyearstargettable += '<td>' + number_format(percentage) + '%<i class="' + class0 + '"></i></td>';
    }
  });

  if (salesTargetForLastThreeYear[year2]) {
    let totalTar2 = salesTargetForLastThreeYear[year2].reduce((a, b) => a + parseFloat(b), 0);
    totalmonthlysalespc2 = (year2total / totalTar2) * 100;
  }

  classt2 = "";
  if (totalmonthlysalespc2 < G_kpithreshold2) {
    classt2 = "bg-red";
  } else if (totalmonthlysalespc2 >= G_kpithreshold2 && stotalmonthlysalespc2 < G_kpithreshold2) {
    classt2 = "bg-yellow";
  } else if (totalmonthlysalespc2 >= G_kpithreshold2) {
    classt2 = "bg-green";
  }
  if (!salesTargetForLastThreeYear[year2]) {
    classt2 = "bg-green";
  }

  percentage = 0;
  if (parseFloat(year1data.reduce((a, b) => a + parseFloat(b), 0))) {
    percentage = (year0data.reduce((a, b) => a + parseFloat(b), 0) / year1data.reduce((a, b) => a + parseFloat(b), 0)) * 100;
  }

  colourPercentage = percentage;
  percentage -= 100;

  class0 = "";
  if (colourPercentage < parseFloat(G_kpithreshold1)) {
    class0 = "red-arrow fa fa-arrow-down";
  } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
    class0 = "yellow-arrow fa fa-arrow-right";
  } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
    class0 = "green-arrow fa fa-arrow-up";
  } else {
    class0 = "green-arrow fa fa-arrow-up";
  }

  twoyearstargettable += '<td class="">' + number_format(percentage) + '%<i class="' + class0 + '"></i></td>';

  twoyearstargettable += `</tr>
    <tr>
        <td><b>` + year2 + `</b></td>
        ` + year2table + `
    </tr>
    <tr class="border-target">
        <td><div style="margin-top: -4px;">%</div></td>`;

  months.forEach(function (i) {
    --i;
    if (yearstartmonth > new Date().getMonth() + 1 && new Date().getMonth() + 1 <= i && i < yearstartmonth - 1) {
      twoyearstargettable += '<td></td>';
    } else if (yearstartmonth <= new Date().getMonth() + 1 && (i < yearstartmonth - 1 || new Date().getMonth() + 1 <= i)) {
      twoyearstargettable += '<td></td>';
    } else {
      percentage = 0;
      if (parseFloat(year2data[i])) {
        percentage = (year0data[i] / year2data[i]) * 100;
      }

      colourPercentage = percentage;
      percentage -= 100;

      class0 = "";
      if (colourPercentage < parseFloat(G_kpithreshold1)) {
        class0 = "red-arrow fa fa-arrow-down";
      } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
        class0 = "yellow-arrow fa fa-arrow-right";
      } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
        class0 = "green-arrow fa fa-arrow-up";
      } else {
        class0 = "green-arrow fa fa-arrow-up";
      }

      twoyearstargettable += '<td>' + number_format(percentage) + '%<i class="' + class0 + '"></i></td>';
    }
  });

  if (salesTargetForLastThreeYear[year2]) {
    let totalTar2 = salesTargetForLastThreeYear[year2].reduce((a, b) => a + parseFloat(b), 0);
    totalmonthlysalespc2 = (year2total / totalTar2) * 100;
  }

  classt2 = "";
  if (totalmonthlysalespc2 < G_kpithreshold2) {
    classt2 = "bg-red";
  } else if (totalmonthlysalespc2 >= G_kpithreshold2 && stotalmonthlysalespc2 < G_kpithreshold2) {
    classt2 = "bg-yellow";
  } else if (totalmonthlysalespc2 >= G_kpithreshold2) {
    classt2 = "bg-green";
  }
  if (!salesTargetForLastThreeYear[year2]) {
    classt2 = "bg-green";
  }

  percentage = 0;
  if (parseFloat(year2data.reduce((a, b) => a + parseFloat(b), 0))) {
    percentage = (year0data.reduce((a, b) => a + parseFloat(b), 0) / year2data.reduce((a, b) => a + parseFloat(b), 0)) * 100;
  }

  colourPercentage = percentage;
  percentage -= 100;

  class0 = "";
  if (colourPercentage < parseFloat(G_kpithreshold1)) {
    class0 = "red-arrow fa fa-arrow-down";
  } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
    class0 = "yellow-arrow fa fa-arrow-right";
  } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
    class0 = "green-arrow fa fa-arrow-up";
  } else {
    class0 = "green-arrow fa fa-arrow-up";
  }

  twoyearstargettable += '<td class="">' + number_format(percentage) + '%<i class="' + class0 + '"></i></td>';

  twoyearstargettable += `
    </tr>
    <tr>
        <td><b>` + year0 + ` Cml.</b></td>`;
  runningTotal = 0;
  year0data.forEach(item => {
    runningTotal += parseFloat(item);
    twoyearstargettable += '<td><b>' + number_format(runningTotal) + '</b></td>';
  });
  twoyearstargettable += '<td><b>' + number_format(year0data.reduce((a, b) => a + parseFloat(b), 0)) + '</b></td>';

  twoyearstargettable += `
    </tr>
    <tr>
        <td><b>` + year1 + ` Cml.</b></td>`;
  runningTotal = 0;
  year1data.forEach(item => {
    runningTotal += parseFloat(item);
    twoyearstargettable += '<td><b>' + number_format(runningTotal) + '</b></td>';
  });
  twoyearstargettable += '<td><b>' + number_format(year1data.reduce((a, b) => a + parseFloat(b), 0)) + '</b></td>';

  twoyearstargettable += `
    </tr>
    <tr>
        <td><div style="margin-top: -4px;">%</div></td>`;

  runningTotalYear0 = 0;
  let runningTotalYear1 = 0;
  months.forEach(function (i) {
    --i;
    runningTotalYear0 += parseFloat(year0data[i]);
    runningTotalYear1 += parseFloat(year1data[i]);

    percentage = 0;
    if (parseFloat(runningTotalYear1)) {
      percentage = (runningTotalYear0 / runningTotalYear1) * 100;
    }

    colourPercentage = percentage;
    percentage -= 100;

    class0 = "";
    if (colourPercentage < parseFloat(G_kpithreshold1)) {
      class0 = "red-arrow fa fa-arrow-down";
    } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
      class0 = "yellow-arrow fa fa-arrow-right";
    } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
      class0 = "green-arrow fa fa-arrow-up";
    } else {
      class0 = "green-arrow fa fa-arrow-up";
    }

    twoyearstargettable += '<td class="">' + number_format(percentage) + '%<i class="' + class0 + '"></i></td>';
  });

  if (salesTargetForLastThreeYear[year2]) {
    let totalTar2 = salesTargetForLastThreeYear[year2].reduce((a, b) => a + parseFloat(b), 0);
    totalmonthlysalespc2 = (year2total / totalTar2) * 100;
  }

  classt2 = "";
  if (totalmonthlysalespc2 < G_kpithreshold2) {
    classt2 = "bg-red";
  } else if (totalmonthlysalespc2 >= G_kpithreshold2 && stotalmonthlysalespc2 < G_kpithreshold2) {
    classt2 = "bg-yellow";
  } else if (totalmonthlysalespc2 >= G_kpithreshold2) {
    classt2 = "bg-green";
  }
  if (!salesTargetForLastThreeYear[year2]) {
    classt2 = "bg-green";
  }

  percentage = 0;
  if (parseFloat(runningTotalYear1)) {
    percentage = (runningTotalYear0 / runningTotalYear1) * 100;
  }

  colourPercentage = percentage;
  percentage -= 100;

  class0 = "";
  if (colourPercentage < parseFloat(G_kpithreshold1)) {
    class0 = "red-arrow fa fa-arrow-down";
  } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
    class0 = "yellow-arrow fa fa-arrow-right";
  } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
    class0 = "green-arrow fa fa-arrow-up";
  } else {
    class0 = "green-arrow fa fa-arrow-up";
  }

  twoyearstargettable += '<td class="">' + number_format(percentage) + '%<i class="' + class0 + '"></i></td>';

  twoyearstargettable += `
    </tr>
    <tr>
        <td><b>` + year2 + ` Cml.</b></td>`;

  runningTotal = 0;
  year2data.forEach(item => {
    runningTotal += parseFloat(item);
    twoyearstargettable += '<td><b>' + number_format(runningTotal) + '</b></td>';
  });
  twoyearstargettable += '<td><b>' + number_format(year2data.reduce((a, b) => a + parseFloat(b), 0)) + '</b></td>';

  twoyearstargettable += `
    </tr>
    <tr class="border-target">
        <td><div style="margin-top: -4px;">%</div></td>`;

  runningTotalYear0 = 0;
  let runningTotalYear2 = 0;
  months.forEach(function (i) {
    --i;
    runningTotalYear0 += parseFloat(year0data[i]);
    runningTotalYear2 += parseFloat(year2data[i]);

    percentage = 0;
    if (parseFloat(runningTotalYear2)) {
      percentage = (runningTotalYear0 / runningTotalYear2) * 100;
    }

    colourPercentage = percentage;
    percentage -= 100;

    class0 = "";
    if (colourPercentage < parseFloat(G_kpithreshold1)) {
      class0 = "red-arrow fa fa-arrow-down";
    } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
      class0 = "yellow-arrow fa fa-arrow-right";
    } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
      class0 = "green-arrow fa fa-arrow-up";
    } else {
      class0 = "green-arrow fa fa-arrow-up";
    }

    twoyearstargettable += '<td class="">' + number_format(percentage) + '%<i class="' + class0 + '"></i></td>';
  });

  if (salesTargetForLastThreeYear[year2]) {
    let totalTar2 = salesTargetForLastThreeYear[year2].reduce((a, b) => a + parseFloat(b), 0);
    totalmonthlysalespc2 = (year2total / totalTar2) * 100;
  }

  classt2 = "";
  if (totalmonthlysalespc2 < G_kpithreshold2) {
    classt2 = "bg-red";
  } else if (totalmonthlysalespc2 >= G_kpithreshold2 && stotalmonthlysalespc2 < G_kpithreshold2) {
    classt2 = "bg-yellow";
  } else if (totalmonthlysalespc2 >= G_kpithreshold2) {
    classt2 = "bg-green";
  }
  if (!salesTargetForLastThreeYear[year2]) {
    classt2 = "bg-green";
  }

  percentage = 0;
  if (parseFloat(runningTotalYear2)) {
    percentage = (runningTotalYear0 / runningTotalYear2) * 100;
  }

  colourPercentage = percentage;
  percentage -= 100;

  class0 = "";
  if (colourPercentage < parseFloat(G_kpithreshold1)) {
    class0 = "red-arrow fa fa-arrow-down";
  } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
    class0 = "yellow-arrow fa fa-arrow-right";
  } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
    class0 = "green-arrow fa fa-arrow-up";
  } else {
    class0 = "green-arrow fa fa-arrow-up";
  }

  twoyearstargettable += '<td class="">' + number_format(percentage) + '%<i class="' + class0 + '"></i></td>';
  twoyearstargettable += '</tr>';

  return twoyearstargettable;
}

function quantityTwoYearsTargetTable(data) {
  let {
    year0,
    year1,
    year2,
    year0total,
    year1total,
    year2total,
    yearstartmonth,
    G_MarginOk,
    G_MarginGood,
    G_kpithreshold1,
    G_kpithreshold2,
    quantityyear0table,
    quantityyear1table,
    quantityyear2table,
    quantityyear0data,
    quantityyear1data,
    quantityyear2data,
  } = data;

  let class0 = '';
  let totalmonthlysalespc2 = 0;
  let stotalmonthlysalespc2 = 0;
  let runningTotalYear0 = 0;
  let runningTotalYear1 = 0;
  G_MarginOk = G_MarginOk ?? 0;
  G_MarginGood = G_MarginGood ?? 0;
  G_kpithreshold1 = G_kpithreshold1 ?? 0;
  G_kpithreshold2 = G_kpithreshold2 ?? 0;

  let quantitytwoyearstargettable = `
    <tr>
      <td><b>` + year0 + `</b></td>` + quantityyear0table + `
    </tr>
    <tr class="">
      <td><div style="margin-top: -4px;">Target</div></td>`;

  for (let i = 1; i <= 12; i++) {
      let pre='';
      if (i < 10) {
          pre='0';
      }
      if (salesTargetForLastThreeYear['monthlysalespc'][year0 + pre + i] < G_MarginOk) class0="bg-red-full";
      else if (salesTargetForLastThreeYear['monthlysalespc'][year0 + pre + i] >= G_MarginOk && salesTargetForLastThreeYear['monthlysalespc'][year0 + pre + i] < G_MarginGood) class0="bg-yellow-full";
      else if (salesTargetForLastThreeYear['monthlysalespc'][year0 + pre + i] >= G_MarginGood) class0="bg-green-full";
      if (!salesTargetForLastThreeYear[year0 + pre + i]) class0="bg-green-full";
      if (i > new Date().getMonth() + 1) { class0=""; }
      quantitytwoyearstargettable += '<td>0</td>';
  }
  if (salesTargetForLastThreeYear[year0]) {
    quantitytwoyearstargettable += '<td>' + number_format(array_sum(salesTargetForLastThreeYear[year0])) + '</td>';
  } else {
    quantitytwoyearstargettable += '<td>0</td>';
  }

  quantitytwoyearstargettable += '</tr>' +
    '<tr class="border-target">' +
    '<td>%</td>';

  for (let i = 1; i <= 12; i++) {
    let pre = '';
    if (i < 10) pre = '0';
    if (yearstartmonth > new Date().getMonth() + 1 && new Date().getMonth() + 1 < i && i < yearstartmonth) {
      quantitytwoyearstargettable += '<td></td>';
    } else if (yearstartmonth <= new Date().getMonth() + 1 && (i < yearstartmonth || new Date().getMonth() + 1 < i)) {
      quantitytwoyearstargettable += '<td></td>';
    } else {
      quantitytwoyearstargettable += '<td class="bg-green-full">0%</td>';
    }
  }

  quantitytwoyearstargettable += '<td class="bg-green-full">0%</td>';
  quantitytwoyearstargettable += `
      </tr>
    <tr>
        <td><b>` + year0 + ` Cml.</b></td> `;

  quantityyear0data = quantityyear0data.slice(1, -1).split(',');
  quantityyear2data = quantityyear2data.slice(1, -1).split(',');
  let runningTotal = 0;
  quantityyear0data.forEach(item => {
    runningTotal += parseFloat(item);
    quantitytwoyearstargettable += '<td><b>' + number_format(runningTotal) + '</b></td>';
  });
  quantitytwoyearstargettable += '<td><b>' + number_format(quantityyear0data.reduce((a, b) => a + parseFloat(b), 0)) + '</b></td>';

  quantitytwoyearstargettable += `
      </tr>
    <tr class="">
        <td><div style="margin-top: -4px;">Target</div></td>`;

  for (let i = 1; i <= 12; i++) {
    let pre = '';
    if (i < 10) pre = '0';
    if (salesTargetForLastThreeYear['monthlysalespc'] && salesTargetForLastThreeYear['monthlysalespc'][year1 + pre + i]) {
      if (salesTargetForLastThreeYear['monthlysalespc'][year1 + pre + i] < G_kpithreshold1) {
        class0 = "bg-red-full";
      } else if (salesTargetForLastThreeYear['monthlysalespc'][year1 + pre + i] >= G_kpithreshold1 && salesTargetForLastThreeYear['monthlysalespc'][year1 + pre + i] < G_kpithreshold2) {
        class0 = "bg-yellow-full";
      } else if (salesTargetForLastThreeYear['monthlysalespc'][year1 + pre + i] >= G_kpithreshold2) {
        class0 = "bg-green-full";
      }
    }
    if (!salesTargetForLastThreeYear[year1 + pre + i]) {
      class0 = "bg-green-full";
    }

    quantitytwoyearstargettable += '<td>0</td>';
  }
  quantitytwoyearstargettable += '<td>0</td>';

  quantitytwoyearstargettable += `
      </tr>
    <tr class="border-target">
        <td>%</td>`;

  quantityyear1data = quantityyear1data.slice(1, -1).split(',');
  runningTotal = 0;
  for (let i = 1; i <= 12; i++) {
    quantitytwoyearstargettable += '<td class="bg-green-full">0%</td>';
  }
  quantitytwoyearstargettable += '<td class="bg-green-full">0%</td>';

  quantitytwoyearstargettable += `
      </tr>
    <tr>
        <td><b>` + year0 + `</b></td>
        ` + quantityyear0table + `
    </tr>
    <tr>
        <td><b>` + year1 + `</b></td>
        ` + quantityyear1table + `
    </tr>
    <tr>
        <td><div style="margin-top: -4px;">%</div></td>`;

  for (let i = 1; i <= 12; i++) {
    if (yearstartmonth > new Date().getMonth() + 1 && new Date().getMonth() + 1 < i && i < yearstartmonth) {
      quantitytwoyearstargettable += '<td></td>';
    } else if (yearstartmonth <= new Date().getMonth() + 1 && (i < yearstartmonth || new Date().getMonth() + 1 < i)) {
      quantitytwoyearstargettable += '<td></td>';
    } else {
      let percentage = 0;
      if (parseFloat(quantityyear1data[i]))
        percentage = (quantityyear0data[i] / parseFloat(quantityyear1data[i])) * 100;

      let colourPercentage = percentage;
      percentage -= 100;

      if (colourPercentage < parseFloat(G_kpithreshold1)) {
        class0 = "red-arrow fa fa-arrow-down";
      } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
        class0 = "yellow-arrow fa fa-arrow-right";
      } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
        class0 = "green-arrow fa fa-arrow-up";
      } else {
        class0 = "green-arrow fa fa-arrow-up";
      }
      quantitytwoyearstargettable += '<td>' + number_format(percentage) + '%<i class="' + class0 + '"></i></td>';
    }
  }

  if (salesTargetForLastThreeYear[year2]) {
    let totalTar2 = salesTargetForLastThreeYear[year2].reduce((a, b) => a + parseFloat(b), 0);
    totalmonthlysalespc2 = (year2total / totalTar2) * 100;
  }

  classt2 = "";
  if (totalmonthlysalespc2 < G_kpithreshold2) {
    classt2 = "bg-red";
  } else if (totalmonthlysalespc2 >= G_kpithreshold2 && stotalmonthlysalespc2 < G_kpithreshold2) {
    classt2 = "bg-yellow";
  } else if (totalmonthlysalespc2 >= G_kpithreshold2) {
    classt2 = "bg-green";
  }
  if (!salesTargetForLastThreeYear[year2]) {
    classt2 = "bg-green";
  }

  percentage = 0;
  if (parseFloat(quantityyear1data.reduce((a, b) => a + b ? parseFloat(b) : 0, 0))) {
    percentage = (quantityyear0data.reduce((a, b) => a + parseFloat(b), 0) / quantityyear1data.reduce((a, b) => a + b ? parseFloat(b) : 0, 0)) * 100;
  }

  colourPercentage = percentage;
  percentage -= 100;

  class0 = "";
  if (colourPercentage < parseFloat(G_kpithreshold1)) {
    class0 = "red-arrow fa fa-arrow-down";
  } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
    class0 = "yellow-arrow fa fa-arrow-right";
  } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
    class0 = "green-arrow fa fa-arrow-up";
  } else {
    class0 = "green-arrow fa fa-arrow-up";
  }

  quantitytwoyearstargettable += '<td class="">' + number_format(percentage) + '%<i class="' + class0 + '"></i></td>';

  quantitytwoyearstargettable += `
      </tr>
    <tr>
        <td><b>` + year2 + `</b></td>
        ` + quantityyear2table + `
    </tr>
    <tr class="border-target">
        <td><div style="margin-top: -4px;">%</div></td>
      `;
  for (let i = 1; i <= 12; i++) {
    if (yearstartmonth > new Date().getMonth() + 1 && new Date().getMonth() + 1 < i && i < yearstartmonth) {
      quantitytwoyearstargettable += '<td></td>';
    } else if (yearstartmonth <= new Date().getMonth() + 1 && (i < yearstartmonth || new Date().getMonth() + 1 < i)) {
      quantitytwoyearstargettable += '<td></td>';
    } else {
      let percentage = 0;
      if (parseFloat(quantityyear2data[i]))
        percentage = (quantityyear0data[i] / parseFloat(quantityyear2data[i])) * 100;

      let colourPercentage = percentage;
      percentage -= 100;

      if (colourPercentage < parseFloat(G_kpithreshold1)) {
        class0 = "red-arrow fa fa-arrow-down";
      } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
        class0 = "yellow-arrow fa fa-arrow-right";
      } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
        class0 = "green-arrow fa fa-arrow-up";
      } else {
        class0 = "green-arrow fa fa-arrow-up";
      }
      quantitytwoyearstargettable += '<td>' + number_format(percentage) + '%<i class="' + class0 + '"></i></td>';
    }
  }

  if (salesTargetForLastThreeYear[year2]) {
    let totalTar2 = salesTargetForLastThreeYear[year2].reduce((a, b) => a + parseFloat(b), 0);
    totalmonthlysalespc2 = (year2total / totalTar2) * 100;
  }

  classt2 = "";
  if (totalmonthlysalespc2 < G_kpithreshold2) {
    classt2 = "bg-red";
  } else if (totalmonthlysalespc2 >= G_kpithreshold2 && stotalmonthlysalespc2 < G_kpithreshold2) {
    classt2 = "bg-yellow";
  } else if (totalmonthlysalespc2 >= G_kpithreshold2) {
    classt2 = "bg-green";
  }
  if (!salesTargetForLastThreeYear[year2]) {
    classt2 = "bg-green";
  }

  percentage = 0;
  if (parseFloat(quantityyear2data.reduce((a, b) => a + b ? parseFloat(b) : 0, 0))) {
    percentage = (quantityyear0data.reduce((a, b) => a + parseFloat(b), 0) / quantityyear2data.reduce((a, b) => a + b ? parseFloat(b) : 0, 0)) * 100;
  }

  colourPercentage = percentage;
  percentage -= 100;

  class0 = "";
  if (colourPercentage < parseFloat(G_kpithreshold1)) {
    class0 = "red-arrow fa fa-arrow-down";
  } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
    class0 = "yellow-arrow fa fa-arrow-right";
  } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
    class0 = "green-arrow fa fa-arrow-up";
  } else {
    class0 = "green-arrow fa fa-arrow-up";
  }

  quantitytwoyearstargettable += '<td class="">' + number_format(percentage) + '%<i class="' + class0 + '"></i></td>';

  quantitytwoyearstargettable += `
      </tr>
    <tr>
        <td><b>` + year0 + ` Cml.</b></td>`;

  runningTotal = 0;
  quantityyear0data.forEach(item => {
    runningTotal += parseFloat(item);
    quantitytwoyearstargettable += '<td><b>' + number_format(runningTotal) + '</b></td>';
  });
  quantitytwoyearstargettable += '<td><b>' + number_format(quantityyear0data.reduce((a, b) => a + parseFloat(b), 0)) + '</b></td>';

  quantitytwoyearstargettable += `
      </tr>
    <tr>
        <td><b>` + year1 + ` Cml.</b></td>`;

  runningTotal = 0;
  quantityyear1data.forEach(item => {
    runningTotal += parseFloat(item);
    quantitytwoyearstargettable += '<td><b>' + number_format(runningTotal) + '</b></td>';
  });
  quantitytwoyearstargettable += '<td><b>' + number_format(quantityyear1data.reduce((a, b) => a + parseFloat(b), 0)) + '</b></td>';

  quantitytwoyearstargettable += `
      </tr>
    <tr>
        <td><div style="margin-top: -4px;">%</div></td>`;

  runningTotalYear0 = 0;
  runningTotalYear1 = 0;
  for (let i = 0; i < 12; i++) {
    runningTotalYear0 += quantityyear0data[i] ? parseFloat(quantityyear0data[i]) : 0;
    runningTotalYear1 += quantityyear1data[i] ? parseFloat(quantityyear1data[i]) : 0;

    let percentage = 0;
    if (runningTotalYear1 !== 0)
      percentage = (runningTotalYear0 / runningTotalYear1) * 100;

    let colourPercentage = percentage;
    percentage -= 100;

    if (colourPercentage < parseFloat(G_kpithreshold1)) {
      class0 = "red-arrow fa fa-arrow-down";
    } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
      class0 = "yellow-arrow fa fa-arrow-right";
    } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
      class0 = "green-arrow fa fa-arrow-up";
    } else {
      class0 = "green-arrow fa fa-arrow-up";
    }

    quantitytwoyearstargettable += '<td>' + number_format(percentage) + '%<i class="' + class0 + '"></i></td>';
  }

  if (salesTargetForLastThreeYear[year2]) {
    let totalTar2 = salesTargetForLastThreeYear[year2].reduce((a, b) => a + parseFloat(b), 0);
    totalmonthlysalespc2 = (year2total / totalTar2) * 100;
  }

  classt2 = "";
  if (totalmonthlysalespc2 < G_kpithreshold2) {
    classt2 = "bg-red";
  } else if (totalmonthlysalespc2 >= G_kpithreshold2 && stotalmonthlysalespc2 < G_kpithreshold2) {
    classt2 = "bg-yellow";
  } else if (totalmonthlysalespc2 >= G_kpithreshold2) {
    classt2 = "bg-green";
  }
  if (!salesTargetForLastThreeYear[year2]) {
    classt2 = "bg-green";
  }

  percentage = 0;
  if (runningTotalYear1 !== 0) {
    percentage = (runningTotalYear0 / runningTotalYear1) * 100;
  }

  colourPercentage = percentage;
  percentage -= 100;

  class0 = "";
  if (colourPercentage < parseFloat(G_kpithreshold1)) {
    class0 = "red-arrow fa fa-arrow-down";
  } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
    class0 = "yellow-arrow fa fa-arrow-right";
  } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
    class0 = "green-arrow fa fa-arrow-up";
  } else {
    class0 = "green-arrow fa fa-arrow-up";
  }

  quantitytwoyearstargettable += '<td class="">' + number_format(percentage) + '%<i class="' + class0 + '"></i></td>';

  quantitytwoyearstargettable += `
      </tr>
    <tr>
        <td><b>` + year2 + ` Cml.</b></td>`;

  runningTotal = 0;
  quantityyear2data.forEach(item => {
    runningTotal += parseFloat(item);
    quantitytwoyearstargettable += '<td><b>' + number_format(runningTotal) + '</b></td>';
  });
  quantitytwoyearstargettable += '<td><b>' + number_format(quantityyear2data.reduce((a, b) => a + parseFloat(b), 0)) + '</b></td>';

  quantitytwoyearstargettable += `
      </tr>
    <tr class="border-target">
        <td><div style="margin-top: -4px;">%</div></td>`;

  runningTotalYear0 = 0;
  runningTotalYear2 = 0;
  for (let i = 0; i < 12; i++) {
    runningTotalYear0 += quantityyear0data[i] ? parseFloat(quantityyear0data[i]) : 0;
    runningTotalYear2 += quantityyear2data[i] ? parseFloat(quantityyear2data[i]) : 0;

    let percentage = 0;
    if (runningTotalYear2 !== 0) {
      percentage = (runningTotalYear0 / runningTotalYear2) * 100;
    }

    let colourPercentage = percentage;
    percentage -= 100;

    if (colourPercentage < parseFloat(G_kpithreshold1)) {
      class0 = "red-arrow fa fa-arrow-down";
    } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
      class0 = "yellow-arrow fa fa-arrow-right";
    } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
      class0 = "green-arrow fa fa-arrow-up";
    } else {
      class0 = "green-arrow fa fa-arrow-up";
    }

    quantitytwoyearstargettable += '<td>' + number_format(percentage) + '%<i class="' + class0 + '"></i></td>';
  }

  if (salesTargetForLastThreeYear[year2]) {
    let totalTar2 = salesTargetForLastThreeYear[year2].reduce((a, b) => a + parseFloat(b), 0);
    totalmonthlysalespc2 = (year2total / totalTar2) * 100;
  }

  classt2 = "";
  if (totalmonthlysalespc2 < G_kpithreshold2) {
    classt2 = "bg-red";
  } else if (totalmonthlysalespc2 >= G_kpithreshold2 && stotalmonthlysalespc2 < G_kpithreshold2) {
    classt2 = "bg-yellow";
  } else if (totalmonthlysalespc2 >= G_kpithreshold2) {
    classt2 = "bg-green";
  }
  if (!salesTargetForLastThreeYear[year2]) {
    classt2 = "bg-green";
  }

  percentage = 0;
  if (runningTotalYear2 !== 0) {
    percentage = (runningTotalYear0 / runningTotalYear2) * 100;
  }

  colourPercentage = percentage;
  percentage -= 100;

  class0 = "";
  if (colourPercentage < parseFloat(G_kpithreshold1)) {
    class0 = "red-arrow fa fa-arrow-down";
  } else if (colourPercentage >= parseFloat(G_kpithreshold1) && colourPercentage < parseFloat(G_kpithreshold2)) {
    class0 = "yellow-arrow fa fa-arrow-right";
  } else if (colourPercentage >= parseFloat(G_kpithreshold2)) {
    class0 = "green-arrow fa fa-arrow-up";
  } else {
    class0 = "green-arrow fa fa-arrow-up";
  }

  quantitytwoyearstargettable += '<td class="">' + number_format(percentage) + '%<i class="' + class0 + '"></i></td></tr>';

  return quantitytwoyearstargettable;
}

function displayPAC(r_pac, canSeeMargins, data_level = '1', account, baseUrl) {
  var baseUrl = baseUrl ? baseUrl : window.location.protocol + "//" + window.location.host + "/";
  let raccount = account;
  let totals = {};
  let html = '';

  r_pac.forEach(row => {
    let code = row['code'];
    let account = row['account'];
    let description = row['description'];
    let qtymtd = row['qtymtd'];
    let salesmtd = row['salesmtd'];
    let marginmtdpc = row['marginmtdpc'];
    let qtyytd = row['qtyytd'];
    let salesytd = row['salesytd'];
    let marginytdpc = row['marginytdpc'];
    let YoY1Sales = row['YoY1Sales'];
    let YoY1ProRataAdjustment = row['YoY1ProRataAdjustment'];
    let YoY1Qty = row['YoY1Qty'];
    let YoY2Sales = row['YoY2Sales'];
    let YoY2Qty = row['YoY2Qty'];

    totals['salesmtd'] += parseFloat(salesmtd);
    totals['qtymtd'] += parseFloat(qtymtd);
    totals['costsmtd'] += parseFloat(row['costsmtd']);
    totals['salesytd'] += parseFloat(salesytd);
    totals['qtyytd'] += parseFloat(qtyytd);
    totals['costsytd'] += parseFloat(row['costsytd']);
    totals['YoY1Sales'] += parseFloat(YoY1Sales);
    totals['YoY1ProRataAdjustment'] += parseFloat(YoY1ProRataAdjustment);
    totals['YoY2Sales'] += parseFloat(YoY2Sales);
    totals['YoY1Qty'] += parseFloat(YoY1Qty);
    totals['YoY2Qty'] += parseFloat(YoY2Qty);

    let salesDifferencePercentage = 0;
    let class0 = "";

    if (YoY1Sales == 0) {
      if (salesytd == 0) {
        salesDifferencePercentage = "0.00";
        class0 = "";
      } else if(salesytd < 0)
      {
        salesDifferencePercentage = "-100.00";
        class0 = "redrow";
      } else
      {
        salesDifferencePercentage = "100.00";
        class0 = "greenrow";
      }
    }

    else {
      salesDifferencePercentage = getDiffPercentage(salesytd, YoY1Sales + YoY1ProRataAdjustment);
      class0 = "";

      if (salesDifferencePercentage < 0) {
        class0 = "redrow";
      }
      else {
        class0 = "greenrow";
      }
    }

    html +=
      `<tr class="` + class0 + `">
        <td> ` + code + `</td>
				<td><a class="iframe cboxElement" href="` + baseUrl + `customer/customergraph/account/` + raccount + `/level/pac` + data_level + `/code/` + code + `">` + description + `</a></td>
				<td>` + salesytd + `</td>
				<td>` + qtyytd + `</td>
				<td>` + number_format(salesDifferencePercentage, 2) + `</td>
				<td>` + getDiffPercentageFormatted(qtyytd, YoY1Qty) + `</td>
				<td>` + number_format(YoY1Sales + YoY1ProRataAdjustment, 2) + `</td>
				<td>` + YoY1Qty + `</td>
				<td>` + YoY2Sales + `</td>
				<td>` + YoY2Qty + `</td>`;

    if (canSeeMargins) {
      html += `
        <td>` + number_format(marginmtdpc, 2) + `</td>
        <td>` + number_format(marginytdpc, 2) + `</td >`;
    }

    html += `
        <td style = "display: none;">` + row['costsmtd'] + `</td>
        <td style = "display: none;">` + row['costsytd'] + `</td >
        <td style = "display: none;">` + row['salesmtd'] + `</td>
			</tr>`;
  });

  return {
    html: html,
    totals: totals
  }
}

function loadingThreeYearsChart(data){
  const {
    targetDataForCurrentYear,
    yearstartmonth,
    cumulativeTargetDataForCurrentYear,
    cumulativeYear0ChartValues,
    cumulativeYear1ChartValues,
    cumulativeYear2ChartValues,
    cumulativeQuantityYear0ChartValues,
    cumulativeQuantityYear1ChartValues,
    cumulativeQuantityYear2ChartValues,
    quantityYear0ChartValues,
    quantityYear1ChartValues,
    quantityYear2ChartValues,
    year0ChartValues,
    year1ChartValues,
    year2ChartValues,
    year0,
    year1,
    year2
  } = data;

  if (year0ChartValues != null && targetDataForCurrentYear != null)
  {
    let labels = ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"];
    for (let i = 1; i < yearstartmonth; i++) {
      var tmp = labels.shift();
      labels.push(tmp);
    }
    var ThisYearVsTargetChartCanvas = $("#this-year-vs-target").get(0).getContext("2d");
    var ThisYearVsTarget = new Chart(ThisYearVsTargetChartCanvas);
    var ThisYearVsTargetChartData =
    {
      labels   : labels,
      datasets :
      [
        {
          label                : year0,
          fillColor            : "#001f3f",
          strokeColor          : "#001f3f",
          pointColor           : "#001f3f",
          pointStrokeColor     : "#001f3f",
          pointHighlightFill   : "#fff",
          pointHighlightStroke : "rgba(220,220,220,1)",
          data                 : eval(year0ChartValues)
        },
        {
          label                : year1,
          fillColor            : "#3c8dbc",
          strokeColor          : "#3c8dbc",
          pointColor           : "#3c8dbc",
          pointStrokeColor     : "#3c8dbc",
          pointHighlightFill   : "#fff",
          pointHighlightStroke : "rgba(60,141,188,1)",
          data                 : eval(targetDataForCurrentYear)
        }
      ]
    };

    var ThisYearVsTargetChartOptions =
    {
      //Boolean - If we should show the scale at all
      showScale                : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines       : false,
      //String - Colour of the grid lines
      scaleGridLineColor       : "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth       : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines : true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines   : true,
      //Boolean - Whether the line is curved between points
      bezierCurve              : true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension       : 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot                 : false,
      //Number - Radius of each point dot in pixels
      pointDotRadius           : 4,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth      : 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius  : 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke            : true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth       : 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill              : false,
      //String - A legend template
      legendTemplate           : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i = 0; i < datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if (datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio      : true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive               : true,
    };
    ThisYearVsTarget.Line(ThisYearVsTargetChartData, ThisYearVsTargetChartOptions);
  }

  if (cumulativeYear0ChartValues != null && cumulativeTargetDataForCurrentYear != null)
  {
    labels = ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"];
    for (let i = 1; i < yearstartmonth; i++) {
      var tmp = labels.shift();
      labels.push(tmp);
    }
  
    var ThisYearCmlVsTargetCmlChartCanvas = $("#this-year-cml-vs-target-cml").get(0).getContext("2d");
    var ThisYearCmlVsTargetCml = new Chart(ThisYearCmlVsTargetCmlChartCanvas);

    var ThisYearCmlVsTargetCmlChartData =
    {
      labels   : labels,
      datasets :
      [
        {
          label                : year0,
          fillColor            : "#001f3f",
          strokeColor          : "#001f3f",
          pointColor           : "#001f3f",
          pointStrokeColor     : "#001f3f",
          pointHighlightFill   : "#fff",
          pointHighlightStroke : "rgba(220,220,220,1)",
          data                 : eval(cumulativeYear0ChartValues)
        },
        {
          label                : year1,
          fillColor            : "#3c8dbc",
          strokeColor          : "#3c8dbc",
          pointColor           : "#3c8dbc",
          pointStrokeColor     : "#3c8dbc",
          pointHighlightFill   : "#fff",
          pointHighlightStroke : "rgba(60,141,188,1)",
          data                 : eval(cumulativeTargetDataForCurrentYear)
        }
      ]
    };

    var ThisYearCmlVsTargetCmlChartOptions =
    {
      //Boolean - If we should show the scale at all
      showScale                : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines       : false,
      //String - Colour of the grid lines
      scaleGridLineColor       : "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth       : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines : true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines   : true,
      //Boolean - Whether the line is curved between points
      bezierCurve              : true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension       : 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot                 : false,
      //Number - Radius of each point dot in pixels
      pointDotRadius           : 4,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth      : 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius  : 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke            : true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth       : 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill              : false,
      //String - A legend template
      legendTemplate           : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i = 0; i < datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if (datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio      : true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive               : true,
    };

    //Create the line chart
    ThisYearCmlVsTargetCml.Line(ThisYearCmlVsTargetCmlChartData,ThisYearCmlVsTargetCmlChartOptions);
  }
  if (year0ChartValues != null && year1ChartValues != null && year2ChartValues != null)
  {
    labels = ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"];
    for (let i = 1; i < yearstartmonth; i++) {
      var tmp = labels.shift();
      labels.push(tmp);
    }
    var ThisYearVsLastYearChartCanvas = $("#this-year-vs-last-year").get(0).getContext("2d");
    var ThisYearVsLastYear = new Chart(ThisYearVsLastYearChartCanvas);

    var ThisYearVsLastYearChartData =
    {
      labels   : labels,
      datasets :
      [
        {
          label                : year0,
          fillColor            : "#001f3f",
          strokeColor          : "#001f3f",
          pointColor           : "#001f3f",
          pointStrokeColor     : "#001f3f",
          pointHighlightFill   : "#fff",
          pointHighlightStroke : "rgba(220,220,220,1)",
          data                 : eval(year0ChartValues),
        },
        {
          label                : year1,
          fillColor            : "#3c8dbc",
          strokeColor          : "#3c8dbc",
          pointColor           : "#3c8dbc",
          pointStrokeColor     : "#3c8dbc",
          pointHighlightFill   : "#fff",
          pointHighlightStroke : "rgba(60,141,188,1)",
          data                 : eval(year1ChartValues),
        },
        {
          label                : year2,
          fillColor            : "#d2d6de",
          strokeColor          : "#d2d6de",
          pointColor           : "#d2d6de",
          pointStrokeColor     : "#d2d6de",
          pointHighlightFill   : "#fff",
          pointHighlightStroke : "rgba(0,133,72,1)",
          data                 : eval(year2ChartValues),
        }
      ]
    };

    var ThisYearVsLastYearChartOptions =
    {
      //Boolean - If we should show the scale at all
      showScale                : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines       : false,
      //String - Colour of the grid lines
      scaleGridLineColor       : "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth       : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines : true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines   : true,
      //Boolean - Whether the line is curved between points
      bezierCurve              : true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension       : 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot                 : false,
      //Number - Radius of each point dot in pixels
      pointDotRadius           : 4,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth      : 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius  : 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke            : true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth       : 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill              : false,
      //String - A legend template
      legendTemplate           : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i = 0; i < datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if (datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio      : true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive               : true,
    };

    //Create the line chart
    ThisYearVsLastYear.Line(ThisYearVsLastYearChartData, ThisYearVsLastYearChartOptions);
  }
  if (cumulativeYear0ChartValues != null && cumulativeYear1ChartValues != null && cumulativeYear2ChartValues != null)
  {
    labels = ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"];
    for (let i = 1; i < yearstartmonth; i++) {
      var tmp = labels.shift();
      labels.push(tmp);
    }
    var ThisYearCmlVsLastYearCmlChartCanvas = $("#this-year-cml-vs-last-year-cml").get(0).getContext("2d");
    var ThisYearCmlVsLastYearCml = new Chart(ThisYearCmlVsLastYearCmlChartCanvas);

    var ThisYearCmlVsLastYearCmlChartData =
    {
      labels   : labels,
      datasets :
      [
        {
          label                : year0,
          fillColor            : "#001f3f",
          strokeColor          : "#001f3f",
          pointColor           : "#001f3f",
          pointStrokeColor     : "#001f3f",
          pointHighlightFill   : "#fff",
          pointHighlightStroke : "rgba(220,220,220,1)",
          data                 : eval(cumulativeYear0ChartValues)
        },
        {
          label                : year1,
          fillColor            : "#3c8dbc",
          strokeColor          : "#3c8dbc",
          pointColor           : "#3c8dbc",
          pointStrokeColor     : "#3c8dbc",
          pointHighlightFill   : "#fff",
          pointHighlightStroke : "rgba(60,141,188,1)",
          data                 : eval(cumulativeYear1ChartValues)
        },
        {
          label                : year2,
          fillColor            : "#d2d6de",
          strokeColor          : "#d2d6de",
          pointColor           : "#d2d6de",
          pointStrokeColor     : "#d2d6de",
          pointHighlightFill   : "#fff",
          pointHighlightStroke : "rgba(0,133,72,1)",
          data                 : eval(cumulativeYear2ChartValues)
        }
      ]
    };

    var ThisYearCmlVsLastYearCmlChartOptions =
    {
      //Boolean - If we should show the scale at all
      showScale                : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines       : false,
      //String - Colour of the grid lines
      scaleGridLineColor       : "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth       : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines : true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines   : true,
      //Boolean - Whether the line is curved between points
      bezierCurve              : true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension       : 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot                 : false,
      //Number - Radius of each point dot in pixels
      pointDotRadius           : 4,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth      : 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius  : 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke            : true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth       : 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill              : false,
      //String - A legend template
      legendTemplate           : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i = 0; i < datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if (datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio      : true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive               : true,
    };

    //Create the line chart
    ThisYearCmlVsLastYearCml.Line(ThisYearCmlVsLastYearCmlChartData, ThisYearCmlVsLastYearCmlChartOptions);
  }

  if (quantityYear0ChartValues != null && quantityYear1ChartValues != null)
  {
    labels = ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"];
    for (let i = 1; i < yearstartmonth; i++) {
      var tmp = labels.shift();
      labels.push(tmp);
    }
    var QuantityThisYearVsLastYearChartCanvas = $("#quantity-this-year-vs-last-year").get(0).getContext("2d");
    var QuantityThisYearVsLastYear = new Chart(QuantityThisYearVsLastYearChartCanvas);

    var QuantityThisYearVsLastYearChartData =
    {
      labels   : labels,
      datasets :
      [
        {
          label                : year0,
          fillColor            : "#001f3f",
          strokeColor          : "#001f3f",
          pointColor           : "#001f3f",
          pointStrokeColor     : "#001f3f",
          pointHighlightFill   : "#fff",
          pointHighlightStroke : "rgba(220,220,220,1)",
          data                 : quantityYear0ChartValues
        },
        {
          label                : year1,
          fillColor            : "#3c8dbc",
          strokeColor          : "#3c8dbc",
          pointColor           : "#3c8dbc",
          pointStrokeColor     : "#3c8dbc",
          pointHighlightFill   : "#fff",
          pointHighlightStroke : "rgba(60,141,188,1)",
          data                 : quantityYear1ChartValues
        },
        {
          label                : year2,
                      fillColor            : "#d2d6de",
                      strokeColor          : "#d2d6de",
                      pointColor           : "#d2d6de",
                      pointStrokeColor     : "#d2d6de",
                      pointHighlightFill   : "#fff",
                      pointHighlightStroke : "rgba(0,133,72,1)",
          data                 : quantityYear2ChartValues
        }
      ]
    };

    var QuantityThisYearVsLastYearChartOptions =
    {
      //Boolean - If we should show the scale at all
      showScale                : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines       : false,
      //String - Colour of the grid lines
      scaleGridLineColor       : "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth       : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines : true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines   : true,
      //Boolean - Whether the line is curved between points
      bezierCurve              : true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension       : 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot                 : false,
      //Number - Radius of each point dot in pixels
      pointDotRadius           : 4,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth      : 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius  : 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke            : true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth       : 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill              : false,
      //String - A legend template
      legendTemplate           : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i = 0; i < datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if (datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio      : true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive               : true,
    };

    //Create the line chart
    QuantityThisYearVsLastYear.Line(QuantityThisYearVsLastYearChartData, QuantityThisYearVsLastYearChartOptions);
  }

  //------------------------------------------------------------------------------------------------------------------
  //-- Quantity This Year Cml. Vs Last Year Cml. Chart --
  //------------------------------------------------------------------------------------------------------------------

  if (cumulativeQuantityYear0ChartValues != null && cumulativeQuantityYear1ChartValues != null)
  {
    labels = ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"];
    for (let i = 1; i < yearstartmonth; i++) {
      var tmp = labels.shift();
      labels.push(tmp);
    }
    var QuantityThisYearCmlVsLastYearCmlChartCanvas = $("#quantity-this-year-cml-vs-target-cml").get(0).getContext("2d");
    var QuantityThisYearCmlVsLastYearCml = new Chart(QuantityThisYearCmlVsLastYearCmlChartCanvas);

    var QuantityThisYearCmlVsLastYearCmlChartData =
    {
      labels   : labels,
      datasets :
      [
        {
          label                : year0,
          fillColor            : "#001f3f",
          strokeColor          : "#001f3f",
          pointColor           : "#001f3f",
          pointStrokeColor     : "#001f3f",
          pointHighlightFill   : "#fff",
          pointHighlightStroke : "rgba(220,220,220,1)",
          data                 : eval(cumulativeQuantityYear0ChartValues)
        },
        {
          label                : year1,
          fillColor            : "#3c8dbc",
          strokeColor          : "#3c8dbc",
          pointColor           : "#3c8dbc",
          pointStrokeColor     : "#3c8dbc",
          pointHighlightFill   : "#fff",
          pointHighlightStroke : "rgba(60,141,188,1)",
          data                 : eval(cumulativeQuantityYear1ChartValues)
        },
        {
          label                : year2,
                      fillColor            : "#d2d6de",
                      strokeColor          : "#d2d6de",
                      pointColor           : "#d2d6de",
                      pointStrokeColor     : "#d2d6de",
                      pointHighlightFill   : "#fff",
                      pointHighlightStroke : "rgba(0,133,72,1)",
          data                 : eval(cumulativeQuantityYear2ChartValues)
        }
      ]
    };

    var QuantityThisYearCmlVsLastYearCmlChartOptions =
    {
      //Boolean - If we should show the scale at all
      showScale                : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines       : false,
      //String - Colour of the grid lines
      scaleGridLineColor       : "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth       : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines : true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines   : true,
      //Boolean - Whether the line is curved between points
      bezierCurve              : true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension       : 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot                 : false,
      //Number - Radius of each point dot in pixels
      pointDotRadius           : 4,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth      : 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius  : 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke            : true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth       : 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill              : false,
      //String - A legend template
      legendTemplate           : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i = 0; i < datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if (datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio      : true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive               : true,
    };

    //Create the line chart
    QuantityThisYearCmlVsLastYearCml.Line(QuantityThisYearCmlVsLastYearCmlChartData, QuantityThisYearCmlVsLastYearCmlChartOptions);
  }
}