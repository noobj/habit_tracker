import {
  startOfToday,
  eachDayOfInterval,
  subYears,
  subDays,
  isSunday,
  format
} from 'date-fns'

// get start of today
const endDate = startOfToday()
let startDate = subYears(endDate, 1)
startDate = subDays(startDate, 7)

/**
 * generate dates for render grid
 * @returns {Date[]} dates - dates for render grid
 */
const getDates = () => {
  let dates = eachDayOfInterval({
      start: startDate,
      end: endDate
    })
    .sort((d1, d2) => d1 < d2 ? -1 : d1 > d2 ? 1 : 0)

  // 剔除 dates 最前面非週日的日期
  dates = dates.slice(dates.indexOf(dates.find(d => isSunday(d))))
  // 只保留 53 週
  if (dates.length / 7 > 53) {
    dates = dates.slice(7)
  }
  return dates
}

/**
 * @typedef Summary
 * @property {Number} id
 * @property {String} date - ex: Apr 8, 2021
 * @property {String} duration - ex: 5h28m
 * @property {Number} level - 1 to 4
 * @property {Number} timestamp - unix timestamp of date
 */

/**
 * generate dates for render grid
 * @returns {Promise<Summary[]>} dates - dates for render grid
 */
const getSummaries = async () => {
  let params = new URLSearchParams
  params.set('start_date', format(startDate, 'yyyy-MM-dd'))
  params.set('end_date', format(endDate, 'yyyy-MM-dd'))
  return await fetch(`/api/summaries?${params.toString()}`, {
      method: 'GET',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
    })
    .then(async function(response) {
      if (response.status == 401) {
        window.location.href = 'api/create_token';
      }

      return response.json();
    })
}

export {
  getDates,
  getSummaries
}
